<?php

require_once 'jwt/BeforeValidException.php';
require_once 'jwt/ExpiredException.php';
require_once 'jwt/SignatureInvalidException.php';
require_once 'jwt/JWT.php';

use \Firebase\JWT\JWT;

class Zoom_Api
{

    //! PLEASE SET FALLOWING VARIABLES
     
      private $zoom_api_key = 'fyQrgCZ1QQ6NNhzPZOSF1A';
      private $zoom_api_secret = 'htXinqN0ufY0ZbenEfYazb1lNGpxwWa1ZUdg';
    
    
    //private $zoom_api_secret = '3nEp9UIhp772sULtthQrsoBTY8QCpA0yyTuo';
    
    protected function sendRequest($data)
    {
        $request_url = 'https://api.zoom.us/v2/users/me/meetings';
        $headers = array(
            "authorization: Bearer " . $this->generateJWTKey(),
            'content-type: application/json'
        );
        $postFields = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if (!$response) {
            throw new Exception($err);
        }
        return json_decode($response);
    }

    //function to generate JWT
    private function generateJWTKey()
    {
        $key = $this->zoom_api_key;
        $secret = $this->zoom_api_secret;
        $token = array(
            "iss" => $key,
            "exp" => time() + 3600 //60 seconds as suggested
        );
        //	$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlI1OWZNMEViUXFPcWNrU0c4dzR2MmciLCJleHAiOjE1OTA1MTM4NDUsImlhdCI6MTU5MDUwODQ0N30.4ch2OZoFM_vZFdqhoMzJX8r8GPYjKlOkV_vUa7LprFc";
        return JWT::encode($token, $secret);
    }

    public function createAMeeting($data = array())
    {
        $post_time  = strtotime($data['date'] . " " . $data['time']);

        // $start_time = gmdate("Y-m-d\TH:i:s", strtotime($post_time->format('Y-m-d H:i:s')));
        $start_time = gmdate("Y-m-d\TH:i:s", $post_time);

        $createAMeetingArray = array();
        if (!empty($data['alternative_host_ids'])) {
            if (count($data['alternative_host_ids']) > 1) {
                $alternative_host_ids = implode(",", $data['alternative_host_ids']);
            } else {
                $alternative_host_ids = $data['alternative_host_ids'][0];
            }
        }
        $createAMeetingArray['topic']      = "Zoom Class Meeting";
        $createAMeetingArray['agenda']     = !empty($data['agenda']) ? $data['agenda'] : "";
        $createAMeetingArray['type']       = !empty($data['type']) ? $data['type'] : 2; //Scheduled
        $createAMeetingArray['start_time'] = $start_time;
        $createAMeetingArray['timezone']   = "Asia/Colombo";   //SL time zone for Zoom
        $createAMeetingArray['password']   = !empty($data['password']) ? $data['password'] : "";
        $createAMeetingArray['duration']   = !empty($data['duration']) ? $data['duration'] : 240;
        $createAMeetingArray['settings']   = array(
            'join_before_host'  => true,
            'host_video'        => !empty($data['option_host_video']) ? true : false,
            'participant_video' => !empty($data['option_participants_video']) ? true : false,
            'mute_upon_entry'   => !empty($data['option_mute_participants']) ? true : false,
            'enforce_login'     => !empty($data['option_enforce_login']) ? true : false,
            'auto_recording'    => !empty($data['option_auto_recording']) ? $data['option_auto_recording'] : "none",
            'alternative_hosts' => isset($alternative_host_ids) ? $alternative_host_ids : ""
        );
        return $this->sendRequest($createAMeetingArray);
    }
}


//Check if a meeting is being set
if (isset($_POST["add_meeting"])) {
    $response = array();


    try {
        $zoom_meeting = new Zoom_Api();

        $data = $_POST["add_meeting"];
        $res = $zoom_meeting->createAMeeting($data);

        //Check if meeting is created
        if (isset($res->code)) {
            $response["success"] = false;
            $response["error"] = $res->message;
        } else {

            //Send data returned by Zoom RESTAPI
            $response["success"] = true;
            $response["meeting_id"] = $res->id;
            $response["start_url"] = $res->start_url;
            $response["join_url"] = $res->join_url;
        }
    } catch (Exception $ex) {
        $response["success"] = false;
        $response["error"] = $ex->getMessage();
    }

    //Send response
    echo json_encode($response);
}
//start_url
//join_url