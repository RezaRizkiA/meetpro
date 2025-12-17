<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    protected $service;

    public function __construct(CalendarService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Controller super bersih, tidak ada logic map/array di sini
        $events = $this->service->getAdminCalendarEvents();

        return Inertia::render('Administrator/Calendar/Index', [
            'events' => $events
        ]);
    }
}
