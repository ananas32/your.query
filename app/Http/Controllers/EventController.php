<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function userEventsList()
    {
        $events = Event::get();

        return response()->json([
            'events' => $events,
        ]);
    }

    public function index()
    {
        $eventsWithOutDate = Event::whereNull('start_at')->get();
        return view('admin.index', compact('eventsWithOutDate'));
    }

    public function create(Request $request)
    {
        $event = new Event();
        $event->user_id = 1;
        $event->title = $request->title;
        $event->background_color = $request->color;
        $event->border_color = $request->color;
        $event->save();
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
