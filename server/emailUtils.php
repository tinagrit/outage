<?php
	require_once 'secret.php';

    const OUTAGE_SUBJ = "Possible Power Outage";
    const OUTAGE_BODY = "Dear %email%, <br><br>Your device (id: %id%) has lost connection with the server, which may indicate a power outage. <br><br>Last ping: %stamp%. <br><br>You will receive another email when this device comes back online. To opt out, please contact <a href='mailto:webmasters@tinagrit.com'>webmasters@tinagrit.com</a>. <br><br>Best Regards, <br>tinagrit.com <br>";

    const RETURN_SUBJ = "Connection Established / Restored";
    const RETURN_BODY = "Dear %email%, <br><br>Your device (id: %id%) is now online. <br><br>You will receive an email when this device loses connection with the server. To opt out, please contact <a href='mailto:webmasters@tinagrit.com'>webmasters@tinagrit.com</a>. <br><br>Best Regards, <br>tinagrit.com <br>";

    function sendEmail($to, $subj, $msg) {
        require_once 'PHPMailer-master/src/Exception.php';
        require_once 'PHPMailer-master/src/PHPMailer.php';
        require_once 'PHPMailer-master/src/SMTP.php';

		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = SMTP_PORT;

            //Recipients
            $mail->addReplyTo('webmasters@tinagrit.com', 'tinagrit.com Webmasters');
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($to, $to);
            $mail->XMailer = 'muleelawat Authentication Email Services';

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subj;
            $mail->Body    = $msg;
            $mail->AltBody = strip_tags($msg);

            $mail->send();
            return true;
		} 
		catch(\PHPMailer\PHPMailer\Exception $e) {
            error_log("Failed to send email:".$e->getMessage());
			return false;
		}
	}
