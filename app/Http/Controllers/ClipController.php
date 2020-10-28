<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use Illuminate\Http\Request;

class ClipController extends Controller
{
    public function index(Request $request)
    {
        $query = Clip::query()->with(['curator'])->orderBy('created_at', 'desc');

        if ($request->has('game') && strlen($request->input('game')) > 0) {
            $query->where('game', urldecode($request->input('game')));
        }

        if ($request->has('title')) {
            $query->where('title', 'LIKE', "%{$request->input('title')}%");
        }
//        dd($query->toSql(), $query->getBindings());

        $clips = $query->paginate($request->input('per_page'))->appends(request()->except('page'));

        return view('clips', compact('clips'));
    }

    public function show(Clip $clip)
    {
        return view('clip', compact('clip'));
    }
}
