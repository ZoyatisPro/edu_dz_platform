<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class SpaceController extends Controller
{
    /**
     * Show the personal space: modes, teacher request and account shortcuts.
     */
    public function index(): Response
    {
        return Inertia::render('space');
    }
}
