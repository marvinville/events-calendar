<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = Event::orderBy('id', 'desc')
            ->first();

        return json_encode(array(
            'eventName' => $event->event_name,
            'dateFrom' => $event->date_from,
            'dateTo' => $event->date_to,
            'checkedDays' => unserialize($event->checked_days),
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        $event = new Event;
        $event->event_name = $data['eventName'];
        $event->date_from = $data['dateFrom'];
        $event->date_to = $data['dateTo'];
        $event->checked_days = serialize($data['checkedDays']);

        if ($event->save()) {
            return true;
        }

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
