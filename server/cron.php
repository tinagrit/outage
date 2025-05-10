<?php
    // Cron job script to check for gaps in pings

    const INTERVAL_SECONDS = 180;
    const ALLOWED_MISSED_PINGS = 2;

    const STAMPS = __DIR__ . "/stamps.json";
    const DEVICES = __DIR__ . "/devices.json";

    $stamps = file_get_contents(STAMPS);
    $stampsParsed = json_decode($stamps);

    $devices = file_get_contents(DEVICES);
    $devicesParsed = json_decode($devices);

    $current = time();

    // for each device in time stamp list
    foreach($stampsParsed as $id=>$stamp) {

        // if interval gap is too long
        if (($current - $stamp) > (INTERVAL_SECONDS * ALLOWED_MISSED_PINGS)) {
            // if device info exists
            if (isset($devicesParsed->{(string)$id})) {
                // if already notified last cron job
                if ($devicesParsed->{(string)$id}->{"notified"} == true) {
                    continue;
                } else {
                    $devicesParsed->{(string)$id}->{"notified"} = true;
                    $devicesOutput = json_encode($devicesParsed);
                    file_put_contents(DEVICES,$devicesOutput);
                }

                // for each email address listed
                foreach($devicesParsed->{(string)$id}->{"notify"} as $email) {
                    
                }
            }
        }
    }

    http_response_code(200);
    exit("Success");