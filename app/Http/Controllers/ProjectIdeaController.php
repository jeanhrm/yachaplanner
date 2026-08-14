<?php

namespace App\Http\Controllers;

use App\Models\ProjectIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectIdeaController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectIdea::where('is_active', true);

        if ($request->problem_type) {
            $query->where('problem_type', $request->problem_type);
        }

        if ($request->level) {
            $query->where(function($q) use ($request) {
                $q->where('level', $request->level)
                  ->orWhere('level', 'Ambos');
            });
        }

        if ($request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        $ideas = $query->orderBy('problem_type')->orderBy('title')->get();

        return view('ideas.index', compact('ideas'));
    }

    public function show(ProjectIdea $idea)
    {
        return view('ideas.show', compact('idea'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:200',
            'description'      => 'required|string',
            'problem_type'     => 'required|string',
            'level'            => 'required|string',
            'methodology'      => 'required|string',
            'steam_connection' => 'required|string',
            'prototype_ideas'  => 'required|string',
            'difficulty'       => 'required|string',
            'duration'         => 'required|string',
        ]);

        ProjectIdea::create([
            'title'               => $request->title,
            'description'         => $request->description,
            'problem_type'        => $request->problem_type,
            'level'               => $request->level,
            'areas'               => $request->areas ?? [],
            'methodology'         => $request->methodology,
            'steam_connection'    => $request->steam_connection,
            'ancestral_knowledge' => $request->ancestral_knowledge,
            'prototype_ideas'     => $request->prototype_ideas,
            'difficulty'          => $request->difficulty,
            'duration'            => $request->duration,
            'is_user_submitted'   => true,
            'submitted_by'        => Auth::id(),
        ]);

        return redirect()->route('ideas.index')
                         ->with('success', '¡Tu idea fue enviada! La revisaremos pronto.');
    }
}