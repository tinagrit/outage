# OUTAGE
Power outage notifier, based on an ESP32 client and a PHP web server

### Table of Contents
1. [How it works](#how-it-works)
2. [Requirements](#requirements)
3. [Set up](#set-up)
4. [Credits](#credits)
___
### How it works
The client device sends a request to the server at every specified interval while it is online. Whenever the server detects a gap in pings larger than the interval length, it notifies the user of a possible power outage.
- A client device sends an HTTP request to a PHP script at a given interval
- Every time the PHP receives a ping, it notes down the timestamp
- A cron job pings another PHP script, which checks if the difference between current time and last device ping has exceeded its interval length
- If that is the case, then the device has stopped pinging. PHP will send an email to the user, then notes down that it has already done so
- If pinging returns after the outage email has been sent, another email is sent to the user to notify them that the device has come back online
___
### Requirements
- **Client side**
    - an ESP32 Dev Kit
    - Arduino IDE
    - stable power and Wi-Fi connection
- **Server side**
    - PHP web server
    - Cron job
    - SMTP (to send emails)
    - [PHPMailer](https://github.com/PHPMailer/PHPMailer)
    - stable power and internet connection
___
### Set up
- **Server side**
    - Download PHPMailer from [GitHub](https://github.com/PHPMailer/PHPMailer), rename the folder to `PHPMailer-master` and put it under `/server/`
    - Enter SMTP information in `/server/secret.php`
    - Change email information in `/server/emailUtils.php`
    - The default ping interval is 3 minutes. If you want to change it, do so on `INTERVAL_SECONDS` in `/server/cron.php`
    - A template is provided for a device in `/server/devices.json` and `/server/stamps.json`. You can modify the device ID (1-9999) and the email addresses to send notification 
    - Host the contents of `/server/` on a PHP server
    - Note the path to `/server/ping.php` on your server. Format it as `https://{your_url}/{path}/ping.php?id={device_id}`. You will need this later as "ping URL"
    - Set up a [Cron job](https://en.wikipedia.org/wiki/Cron) with interval `*/3 * * * *` if your interval is every 3 minutes. The cron job should fetch the path to `/server/cron.php` (you can use `curl`)
    - If your server doesn't work with cron jobs, you can also use [cron-job.org](https://cron-job.org/en/)

- **Client side**
    - Connect the ESP32 to a computer, and download necessary softwares from the Arduino IDE's board manager
    - Copy and paste the code from `/client/ping.cpp`
    - Enter the Wi-Fi name in `SSIDNAME`, password in `PASSWORD`, and the ping URL in `ENDPOINT`. (Make sure the `id` is present in the URL)
    - Change the ping interval on `INTERVAL_SECONDS` to match the server side, if you have modified it
    - Upload the code on to the ESP32
    - Unplug the ESP32 and plug it into a power source, and make sure that during normal operations, both the red and blue LEDs are lit up

___
### Credits
Made by [Mu Leelawat](https://github.com/tinagrit). Contact information can be found on [my website](https://tinagrit.com/#contact).

Fun fact: This project is live, up and running at my house!