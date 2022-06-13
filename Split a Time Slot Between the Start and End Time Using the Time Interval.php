<?php

foreach ($request->get('times', []) as $time) {

    // first way -- 01
    //========================================================
    $startTime = $time['start_time'];
    $endTime = $time['end_time'];
    $duration = 50;
    $returnTimes = array();// Define output
    $startTime = strtotime($startTime); //Get Timestamp
    $endTime = strtotime($endTime); //Get Timestamp

    $addMins = $duration * 60;

    while ($startTime <= $endTime) //Run loop
    {
        $returnTimes[] = date("G:i", $startTime);
        ModelName::create([
            'start_time' => date("G:i", $startTime),
            'end_time' => Carbon::parse(date("G:i", $startTime))->addMinutes($duration),
        ]);
        $startTime += $addMins; //End time check
    }
    // end first way -- 01

    // second way -- 02
    //========================================================
    $period = new CarbonPeriod($time['start_time'], $duration_mins . ' minutes', $time['end_time']);
    foreach ($period as $item) {
        ModelName::create([
            'start_time' => $item->format("H:i"),
            'end_time' => Carbon::parse($item->format("H:i"))->addMinutes($duration),
        ]);
    }
    // end second way -- 02

}