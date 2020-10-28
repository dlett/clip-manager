<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use Illuminate\Http\Request;

class ClipController extends Controller
{
    public function index(Request $request)
    {
        $clips = Clip::query()->with(['curator'])->paginate($request->input('per_page'));

        return view('clips', compact('clips'));
    }

    public function show(Clip $clip)
    {
        return view('clip', compact('clip'));
    }
}
