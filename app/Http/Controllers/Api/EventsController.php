<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class EventsController extends Controller
{
    public function index(){
        echo "hello";
    }

    /**
     * Display events
     */
    public function getEvents()
    {
        $events = Event::where('is_deleted', 0)->get();
        
        return json_encode(array('data' => $events));
    }

     /**
     * Create Event
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        $data = new Event();
        $data->name = $validated['name'];
        $data->slug = $validated['slug'];
        $data->save();

        return response()->json($data);

        $details = [
            'title' => 'New Event created',
            'body' => 'You created this event title '.$data->name
        ];
    
        \Mail::to(Auth::user()->email)->send(new \App\Mail\MyMail($details));
           
    }

     /**
     * View event
     */
    public function show(Request $request) {
        $event = Event::find($request->id);
    }

     /**
     * Update Event
     */
    public function update(Request $request, $id) {
        $event = findOrFail($id)->update($request->all());
       
        if($event) {
            // Delete event_$id from Redis
            Redis::del('event_' . $id);
      
            $event = Event::find($id);
            // Set a new key with the event id
            Redis::set('blog_' . $id, $event);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'Event updated',
                'data' => $event,
            ]);
        }
    }

    /**
     * Delete Event
     */
    public function delete(Request $request, $id) {

        $event = Event::findOrFail($id);

        if($event){
            Redis::del('blog_' . $id);
      
            $event = Event::find($request->id);
            $event->is_deleted = 1;
            $event->save();

            return response()->json([
                'status_code' => 201,
                'message' => 'Event deleted'
            ]);
        }
    }
    
}

