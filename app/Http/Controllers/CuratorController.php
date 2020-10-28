<?php

namespace App\Http\Controllers;

use App\Models\Curator;

class CuratorController
{
    public function show(Curator $curator)
    {
        return view('curator', compact('curator'));
    }
}
