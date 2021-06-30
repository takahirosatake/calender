<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calendar;

class CalendarController extends Controller
{
    public function getAllEvent()
    {
        $calendars = Calendar::all();
        return $calendars;
    }

    public function createEvent(Request $request)
    {
        $calendar = new Calendar();
        $calendar->name = $request->name;
        $calendar->start = $request->start;
        $calendar->end = $request->end;

        $calendar->save();
    }

    public function deleteEvent(Request $request)
    {
        // idのものを1件取得する
        $calendar = Calendar::where('id', '=', $request->id)
            ->first();
        if ($calendar) {
            $calendar->delete();
        }
    }
}
