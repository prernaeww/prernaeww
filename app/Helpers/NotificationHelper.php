<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use CommonHelper;

use App\Models\Notification;
use App\Models\Devices;
use App\Models\User;
use App\Models\SystemConfig;
use Twilio\Rest\Client;
class NotificationHelper
{
    public static function send($device_token = '', $push_title = '', $push_data = array(), $push_type = '', $notification_arr = [])
    {
        $user_id = Devices::where('token', $device_token)->value('user_id');
        
        if (User::where('id', $user_id)->value('notification')) {
            $API_SERVER_KEY = CommonHelper::ConfigGet('fcm_key');

            $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'to' => $device_token,
                'notification' => array(
                    'title' => $push_title,
                    'body' => $push_data,
                    'type' => $push_type,
                    'sound' => 'Default',
                    'image' => 'Notification Image',
                    'notification_type' => isset($notification_arr) ? $notification_arr['type'] : '',
                    'order_id' => isset($notification_arr) ? $notification_arr['order_id'] : '',
                    // 'order' => isset($notification_arr['order']) ? $notification_arr['order'] : '',
                    'data' => $notification_arr
                ),
                'data' => array(
                    'title' => $push_title,
                    'body' => $push_data, 
                    'type'=> $push_type,
                    'sound' => 'Default',
                    'image' => 'Notification Image',
                    'notification_type' => isset($notification_arr) ? $notification_arr['type'] : '',
                    'order_id' => isset($notification_arr) ? $notification_arr['order_id'] : '',
                    // 'order' => isset($notification_arr['order']) ? $notification_arr['order'] : '',
                    'data' => $notification_arr
                ),
            );            
            $headers = array(
            'Authorization:key=' . $API_SERVER_KEY,
            'Content-Type:application/json',
            );

            // Open connection

            $ch = curl_init();
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);

            curl_close($ch);
            // print_r($result);exit;
        }   
        return true;
        //return strtoupper($message);
    }

    public static function send_bulk($tokens = '', $push_title = '', $push_message = array(), $push_type = '')
    {
        $res['success'] = '0';
        $res['failure'] = '0';
        if(isset($tokens))
        {
            $API_SERVER_KEY = CommonHelper::ConfigGet('fcm_key');

            $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';        
            $push_data = array(
                'body' => $push_message,
                'title' => $push_title,
                'sound' => "Default",
                'type' => $push_type
            );
            $fields = array(
                'registration_ids' => $tokens,
                'notification' => $push_data,
                'data' => $push_data
            );        
            $headers = array('Authorization:key=' . $API_SERVER_KEY, 'Content-Type:application/json', );

            // Open connection

            $ch = curl_init();
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($result);            
            $res['success'] = $json->success;
            $res['failure'] = $json->failure;
        }
            //return strtoupper($message);
        return $res;
    }


    public static function add($user_id = '', $message = '', $type = '', $order_id = '')
    {
        $data = array(
            'user_id' => $user_id,
            'message' => $message,
            'type' => $type,
            'order_id' => $order_id,
        );
        Notification::insert($data);
        return true;
    }

    public static function send_sms($message, $recipient)
    {  
        if($recipient != '' && $message != ''){
            $recipient = '+1'.$recipient;
            $account_sid = SystemConfig::where('path','twilio_account_sid')->value('value');
            $auth_token = SystemConfig::where('path','twilio_auth_token')->value('value');
            $twilio_number = SystemConfig::where('path','twilio_twilio_number')->value('value');
            $client = new Client($account_sid, $auth_token);
            try{
                $phone_number = $client->lookups->v1->phoneNumbers($recipient)->fetch(array( "Type" => array("carrier")));

                // if($phone_number->carrier['error_code'] == '' && $phone_number->carrier['type'] != ''){
                if($phone_number){
                    try {
                        $result = $client->messages->create($recipient, ['from' => $twilio_number, 'body' => $message]);
                        $data['status'] = TRUE;
                        $data['data'] = $result;
                        //print_r($result);
                    } catch (\Exception $e) {
                        $data['status'] = FALSE;
                        $data['error'] = $e->getMessage();
                    }

                }else{
                    $data['status'] = FALSE;
                    $data['error'] = 'Invalid phone number type.';
                }
                
            }catch (\Exception $e){
                $data['status'] = FALSE;
                $data['error'] = $e->getMessage();
            }

        }else{
            $data['status'] = FALSE;
            $data['error'] = "Parameter Missing";
        }
        
        return $data;             
    }
    
}
