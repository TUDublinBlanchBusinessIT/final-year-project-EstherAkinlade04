<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function download(Booking $booking)
    {
        // Get the related fitness class
        $class = $booking->fitnessClass;

        // Format dates to UTC (required for calendar apps)
        $start = Carbon::parse($class->start_time)->utc()->format('Ymd\THis\Z');
        $end = Carbon::parse($class->end_time)->utc()->format('Ymd\THis\Z');

        // Safe filename
        $safeName = str_replace(' ', '-', strtolower($class->name));
        $filename = "vault-gym-{$safeName}.ics";

        // Generate ICS content
        $content = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Vault Gym//EN
BEGIN:VEVENT
UID:" . uniqid() . "@vaultgym.com
DTSTAMP:" . now()->utc()->format('Ymd\THis\Z') . "
SUMMARY:{$class->name}
DESCRIPTION:Your booking at Vault Gym
LOCATION:Vault Gym
DTSTART:$start
DTEND:$end
END:VEVENT
END:VCALENDAR";

        // Return as downloadable file
        return response($content)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}