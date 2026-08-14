<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SteamJourneyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('journey.index', compact('user'));
    }

    public function advance(Request $request)
    {
        $request->validate([
            'stage_data' => 'nullable|array',
        ]);

        $user = Auth::user();
        $user->advanceStage($request->stage_data ?? []);

        return response()->json([
            'success'   => true,
            'new_stage' => $user->fresh()->steam_stage,
            'completed' => $user->fresh()->projects_completed,
            'badge'     => $user->fresh()->getCurrentBadge(),
        ]);
    }

    public function reset()
    {
        $user = Auth::user();
        $user->update([
            'steam_stage'     => 0,
            'current_journey' => [],
        ]);
        return redirect()->route('journey.index');
    }
}