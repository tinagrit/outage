<?php 
    // Device will send a HTTP request to this URL on every specified interval

    const STAMPS = __DIR__ . "/stamps.json";
    const DEVICES = __DIR__ . "/devices.json";

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit("Device ID not given");
    }

    $id = $_GET['id'];

    if (!ctype_digit($id) || (int)$id < 1 || (int)$id > 9999) {
        http_response_code(400);
        exit("Device ID invalid");
    }

    if (!file_exists(STAMPS)) {
        http_response_code(503);
        exit("Internal Server Error");
    }

    $stamps = file_get_contents(STAMPS);
    $stampsParsed = json_decode($stamps);

    $stampsParsed->{$_GET['id']} = time();

    $stampsOutput = json_encode($stampsParsed);
    file_put_contents(STAMPS,$stampsOutput);

    $devices = file_get_contents(DEVICES);
    $devicesParsed = json_decode($devices);
    if (isset($devicesParsed->{(string)$id})) {

        if ($devicesParsed->{(string)$id}->{"notified"} == true) {
            $devicesParsed->{(string)$id}->{"notified"} = false;
            $devicesOutput = json_encode($devicesParsed);
            file_put_contents(DEVICES,$devicesOutput);

            // for each email address listed
            foreach($devicesParsed->{(string)$id}->{"notify"} as $email) {
                require_once 'emailUtils.php';
                $subject = RETURN_SUBJ;
                $body = str_replace(
                    array("%email%","%id%","%stamp%"),
                    array($email,$id,$stamp),
                    RETURN_BODY
                );
                sendEmail($email,$subject,$body);
            }
        } 
    }

    http_response_code(200);
    exit("Success");