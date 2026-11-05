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
            'stage'             => 'required|integer|min:0|max:4',
            'stage_data'        => 'nullable|array',
            'stage_data.action' => 'nullable|string|max:255',
            'stage_data.badge'  => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Evita avanzar dos etapas por doble clic o pestañas desactualizadas
        if ((int) $request->stage !== $user->getCurrentStage()) {
            return response()->json(['success' => false, 'message' => 'Etapa desactualizada'], 409);
        }

        $before = $user->projects_completed ?? 0;
        $user->advanceStage($request->only('stage_data.action', 'stage_data.badge')['stage_data'] ?? []);
        $user->refresh();

        return response()->json([
            'success'          => true,
            'new_stage'        => $user->steam_stage,
            'completed'        => $user->projects_completed,
            'project_complete' => $user->projects_completed > $before,
            'badge'            => $user->getCurrentBadge(),
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