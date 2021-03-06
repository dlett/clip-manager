<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use App\Models\Curator;
use Illuminate\Http\Request;

class CuratorController
{
    public function index(Request $request)
    {
        $curators = Curator::query()->withCount('clips')->orderBy('clips_count', 'desc')->paginate($request->input('per_page'));

        return view('curators', compact('curators'));
    }

    public function show(Curator $curator, Request $request)
    {
        $clips = Clip::indexQuery(collect($request->all()), $curator)
            ->paginate($request->input('per_page'))
            ->appends(request()->except('page'));

        return view('curator', compact('curator', 'clips'));
    }
}
