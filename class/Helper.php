<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;  

class Helper {

    public static function randamId() {



        $today = time();

        $startDate = date('YmdHi', strtotime('1912-03-14 09:06:00'));

        $range = $today - $startDate;

        $rand = rand(0, $range);

        $randam = $rand . "_" . ($startDate + $rand) . '_' . $today . "_n";

        return $randam;
    }

    public function calImgResize($newHeight, $width, $height) {



        $percent = $newHeight / $height;

        $result1 = $percent * 100;



        $result2 = $width * $result1 / 100;



        return array($result2, $newHeight);
    }

    public static function getSitePath() {

//        return substr_replace(dirname(__FILE__), '', 26);

        $path = str_replace('class', '', dirname(__FILE__));

        return $path;
    }

    public function checkIsOnline($last_action_time) {

        date_default_timezone_set('Asia/Colombo');
        $today = new DateTime(date("Y-m-d"));
        $todaytime = new DateTime(date("H:i:s"));

        $arr = explode(' ', $last_action_time);
        $date1 = new DateTime(date($arr[0]));
        $time1 = new DateTime(date($arr[1]));

        $date = $today->diff($date1);
        $datediff = $date->format('%a');

        if ($datediff == 0) {

            $time = $todaytime->diff($time1);
            $timediff = $time->format('%h:%i:%s');

            $arr1 = explode(':', $timediff);
            if ($arr1[0] == 0 && $arr1[1] <= 10) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    } 
    
    public static function Email($email,$subject,$html,$name = '',$sender_name= 'travel@slysc.lk noreply') {
 
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                       // Enable verbose debug output
            $mail->isSMTP();                            // Send using SMTP
            $mail->Host       = "slysc.lk";     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                   // Enable SMTP authentication
            $mail->Username   = "travel@slysc.lk";  // SMTP username
            $mail->Password   = "D50X52f[n8Yp";  // SMTP password
            $mail->SMTPSecure = 'ssl';                   // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = "465";      // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('travel@slysc.lk', $sender_name);
            $mail->addAddress($email, $name);     // Add a recipient  

            // Content
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';                                 // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $html;
            return $mail->send();
        } catch (Exception $e) {
            return false;
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function SendMail() {



        $to = '<' . $this->email . '>';
        $subject = 'Verification Code -' . $this->email_code . '- MyTravelPartner.lk ';
        $from = 'MyTravelPartner.LK NOREPLY <noreply@mytravelpartner.lk>';

// To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
        $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

// Compose a simple HTML email message
        $message = '<html>';
        $message .= '<body>'
                . '<table width="100%" cellspacing="0" cellpadding="0" border="0"  >
    <tbody>
        <tr>
            <td align="center">
                <table style="max-width:660px;" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                    <tbody>
                        <tr>
                            <td bgcolor="#3b5998">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td><h1 style="margin-top:20px;margin-bottom:0px;color: #fff;font-size: 38px;font-family: Arial,Helvetica,sans-serif;letter-spacing: 2px" align="center">MY TRAVEL PARTNER</h1>
                                                                <h2 style="text-align:center;color: #fff;margin: 5px 0px 15px 0px;font-size: 24px;font-family: Arial,Helvetica,sans-serif;letter-spacing: 2px"><span class="il">Travel With Us Today</span></h2>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="max-width:660px;border: 1px solid #999;" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td style="color:#000;font-family:Arial,Helvetica,sans-serif;font-size:18px;font-style:normal;font-weight:500;line-height:28px;padding:25px 34px 0px 40px;font-family: Arial,Helvetica,sans-serif;" align="left">Thank Your For Join With Us..!</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#6d6e70;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-style:normal;font-weight:500;line-height:28px;padding:10px 40px 5px 40px" align="left">Hi,<span style="color:#3b5998"><b> Mohamed atheeb,</b></span></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#6d6e70;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-style:normal;font-weight:500;line-height:22px;padding:0px 40px 20px 40px;text-align:justify" align="left">Thank you for visiting <a href="http://www.vtabaddegama.com" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://www.vtabaddegama.com&amp;source=gmail&amp;ust=1594792970011000&amp;usg=AFQjCNFzdHU8oUdnLoC7HdloCE_SKVqqqQ">www.vtabaddegama.com</a> web site and contacting us. Your enquiry has been sent to Vocational Training Institute - <span class="il">Baddegama</span>. And one of representative will be contact you shortly. 	 
                                                The details of your enquiry are shown below.</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-style:normal;font-weight:500;line-height:22px;padding:0px 40px 20px 40px;text-align:justify" align="left">
                                                * Please use your email - ***<a href="otec@gmail.com" target="_blank">otec@gmail.com</a> and your password to login.Use this <a href="http://thaksalawa.lk/new/lecture" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://thaksalawa.lk/new/lecture&amp;source=gmail&amp;ust=1594796972390000&amp;usg=AFQjCNHFoucOm6AR4CzM8XIMWOc0p_mxbw"><span>link....! <span></span></span></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-style:normal;font-weight:600;line-height:28px;padding:0px 40px 0px 40px;text-align:justify" align="left"> NOTE: Please Do not reply to this email</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#6d6e70;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-style:normal;font-weight:500;line-height:18px;padding:0px 40px 25px 40px" align="left">This email was generated for notification purpose. You are receiving this email because you are a member of Calling.lk web Site. </td>
                                        </tr>

                                        <tr bgcolor="#3b5998">
                                            <td   align="center">
                                                <table style="max-width:660px;margin-bottom: 15px;margin-top: 15px;" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="color:#7f8c8d;font-family:Helvetica,Arial,sans-serif;font-size:12px;line-height:18px;"   align="left">
                                                                <table cellspacing="0" cellpadding="0" border="0" align="center">
                                                                    <tbody>
                                                                        <tr style="padding-bottom:10px;padding-top:10px;color:#fff">
                                                                            <td align="center">My Travel Partner Private Limited - <span class="il">Sri Lanka</span>. </td>
                                                                        </tr>
                                                                        <tr  >
                                                                            <td style="padding-bottom:10px;padding-top:10px"> 
                                                                                <a style="color:#fff" href="https://paidera.com/?utm_source=email_footer&amp;utm_medium=email" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://paidera.com/?utm_source%3Demail_footer%26utm_medium%3Demail&amp;source=gmail&amp;ust=1594794652476000&amp;usg=AFQjCNE2Eeo0vCvm-jZeZYWoOvpn7ANjdw"><span class="il">mytravelpartner</span>.lk</a> |
                                                                                <a style="color:#fff" href="https://paidera.com/dashboard/tickets?utm_source=email_footer&amp;utm_medium=email" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://paidera.com/dashboard/tickets?utm_source%3Demail_footer%26utm_medium%3Demail&amp;source=gmail&amp;ust=1594794652476000&amp;usg=AFQjCNFPDzomxLpw8Kh4TZnvv4CGcN2EfQ">Help &amp; Support</a> |
                                                                                <a style="color:#fff" href="https://paidera.com/policy?utm_source=email_footer&amp;utm_medium=email" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://paidera.com/policy?utm_source%3Demail_footer%26utm_medium%3Demail&amp;source=gmail&amp;ust=1594794652476000&amp;usg=AFQjCNF1y6JPOlUk9aAnkMK6fsrNzzJNFA">Privacy Policy</a> |
                                                                                <a style="color:#fff"  href="https://t.me/paidera" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://t.me/paidera&amp;source=gmail&amp;ust=1594794652476000&amp;usg=AFQjCNHFn1rQKnJdlBmETqBK7L9vOkKzAw">Term and Conditions</a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td> 
                        </tr>
                    </tbody> 
                </table>  
            </td>
        </tr>
    </tbody>
</table><div class="yj6qo"></div><div class="adL">
</div>';

        $message .= '</body>';
        $message .= '</html>';



        if (mail($to, $subject, $message, $headers)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
