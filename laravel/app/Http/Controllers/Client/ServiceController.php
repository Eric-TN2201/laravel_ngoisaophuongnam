<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::posted()->latest()->get();
        return view('client.service.index', compact('services'));
    }

    public function show(Service $service)
    {
        abort_unless($service->status == 1, 404);
        return view('client.service.show', compact('service'));
    }
}
