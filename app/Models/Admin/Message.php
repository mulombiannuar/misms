<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $primaryKey = 'message_id';
    
    public function getGreetings($username)
    {
        date_default_timezone_set("Africa/Nairobi");
        $greetings = '';
        $time = date("H");
        if ($time < "12") 
        {
            $greetings = "Good morning ".$username;
        }
        elseif ($time >= "12" && $time < "15")
        {
            $greetings = "Good afternoon ".$username;
        }
        else
        {
            $greetings = "Good evening ".$username;
        }
        return $greetings;
    } 

    public function sendSms($mobileNo, $messageBody)
    {
        $sender = 'BIMAS';
        $user_id ='15571' ; 
        $smsGatewayUrl = 'https://api.prsp.tangazoletu.com/?';
        $passkey = '2CFKzjE9K3'; 
       
        $textmessage = urlencode($messageBody);
        $api_params ='User_ID='.$user_id.'&passkey='.$passkey.'&service=1&sender='.$sender.'&dest='.$mobileNo.'&msg='.$textmessage;
        $url = $smsGatewayUrl.$api_params;
      
        try 
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $smsGatewayUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $api_params);
            // curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
            // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            // curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            $server_output = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                file_put_contents("log.txt", $error_msg . " \n", FILE_APPEND);
            }
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $IP = $_SERVER['HTTP_CLIENT_IP'];
            } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $IP = $_SERVER['REMOTE_ADDR']; 
            }
            file_put_contents("log.txt", $server_output . " \n", FILE_APPEND);
            file_put_contents("log.txt", $IP . " IP 1\n", FILE_APPEND);
            // file_put_contents("log.txt", $_SERVER['REMOTE_ADDR'] . " IP 2\n", FILE_APPEND);
            curl_close($ch);
        } catch (\Throwable $e) {
            file_put_contents("log.txt", $e . " \n", FILE_APPEND);
        }
    }
    

}