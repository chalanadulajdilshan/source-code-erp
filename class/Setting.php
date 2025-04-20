<?php

/**
 * Description of User
 *
 * @author sublime holdings
 * @web www.sublime.lk
 */
class Setting {

    public $domain = 'xxx';
 
    public function sendSecurityAlert($domain) {

        date_default_timezone_set('Asia/Colombo');
        $subject = 'Security Alert - ' . $domain;

        $headers = "From: mail@" . $this->domain . "\r\n";
        $headers .= "Reply-To: mail@" . $this->domain . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $html = '<h3>Security Alert - ' . $domain . '</h3>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>Client IP Address: </td>';
        $html .= '<td>' . getUserIP() . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Date: </td>';
        $html .= '<td>' . date("l, F j, Y") . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Time: </td>';
        $html .= '<td>' . date("g:i a") . '</td>';
        $html .= '</tr>';
        $html .= '<td>Referer Url: </td>';
        $html .= '<td>' . parse_url($_SERVER['HTTP_REFERER'])["host"] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        mail('ygmchathu@gmail.com', $subject, $html, $headers);

        echo '<script>alert("Invalid security token!..."); window.location.replace("http://www.sublime.lk");</script>';
        exit();
    }

    public function getUserIP() {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        } 
        return $ip;
    }

    public function url() {
        return "http://" . parse_url($_SERVER['HTTP_REFERER'])['host'] . "/";
    }

}
