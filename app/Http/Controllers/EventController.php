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

        return response()->json([
            'id' => $event->id,
        ]);
    }

    public function update(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $event->start_at = \Carbon\Carbon::parse($request->start_at)->addHours(3);
        $event->end_at = \Carbon\Carbon::parse($request->end_at)->addHours(3);
        $event->save();
    }

    public function saveInfo(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $file = $request->file('image');
        if($file) {
            $path = str_replace("public/", "", $file->store('public/event-images'));
            $event->image = $path;
        }

        $file = $request->file('file');
        if($file) {
            $path = str_replace("public/", "", $file->store('public/event-files'));
            $event->file = $path;
        }

        $event->text = $request->text;
        $event->save();
        return redirect()->back()->with('success', 'Дані успішно збережені');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.event-edit', compact('event'));
    }

    public function delete()
    {
    }
}
