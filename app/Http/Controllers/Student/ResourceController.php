<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('created_at', 'desc')->get();
        return view('student.resources.index', compact('resources'));
    }
}
