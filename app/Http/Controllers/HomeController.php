<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
        ->withCount(['orders' => fn($q)=> $q->whereNotIn('status', ['cancelled'])])
        ->get();
        return view('home', compact('services'));
    }
}
