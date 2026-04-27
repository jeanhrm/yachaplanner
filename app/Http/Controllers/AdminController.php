<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::query()
            ->when($search, fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('ugel', 'like', "%{$search}%")
            )
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'total'       => User::count(),
            'pro'         => User::where('plan', 'pro')->count(),
            'institution' => User::where('plan', 'institution')->count(),
            'free'        => User::where('plan', 'free')->orWhereNull('plan')->count(),
        ];

        return view('admin.index', compact('users', 'stats', 'search'));
    }

    public function updatePlan(Request $request, User $user)
    {
        $request->validate([
            'plan' => 'required|in:free,pro,institution',
        ]);

        $creditsMap = [
            'free'        => 5,
            'pro'         => 999,
            'institution' => 999,
        ];

        $user->update([
            'plan'                 => $request->plan,
            'weekly_credits_used'  => 0,
        ]);

        return back()->with('success', "Plan de {$user->name} actualizado a {$request->plan}.");
    }

    public function addCredits(Request $request, User $user)
    {
        $request->validate([
            'credits' => 'required|integer|min:1|max:100',
        ]);

        $user->decrement('weekly_credits_used', $request->credits);

        return back()->with('success', "{$request->credits} créditos agregados a {$user->name}.");
    }
}