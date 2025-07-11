<?php 
include_once('crud.php');

class Firebase {
    protected $db;
        function __construct(){
            $this->db = new Database();
            $this->db->connect();
            date_default_timezone_set('Asia/Kolkata');
            }

    public function send($registration_ids, $message) {
        // echo 'registration id :'.$registration_ids;
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
    
    /*
    * This function will make the actuall curl request to firebase server
    * and then the message is sent 
    */
    private function sendPushNotification($fields) {
    $url = 'https://fcm.googleapis.com/v1/projects/fortuneappcompany/messages:send';
    
    $accessToken = 'ya29.c.c0ASRK0GZv_NN2zQva7hxGf9WbWrV9xU9qgD76wsMeuTcDMUn7B2Wr_vJMpM-OYtTcotxQd9T15LlD3x3tIZ3fNoJA9soaW-M0LRjhT2ut7PdC42jF1jCXvF3UwVi1sETHphSp8kpav_J_0nEK_46hImm07S7H4voZJNWSTnEtUL-1kI6FQhqhg9f3fuBjRrOqmoEjPakFjI_81gz_uOeBvMLY6urjLSE64hvpzfplv3TM3NA29V7LoZVx00a_ICt-ipQRxNmlc6ExlJNCGBkYh8XBSxbp_GxZJSmSq8FAw_2CuJTAO8dcm52dJGFzQH6Jw1dbnbTLPRFcCWIU1xAhXO3cbqVuD4AwkaqJTGCKaYgwkOBLh1gkfDoE384P66vz-w5SMB9VsgeqMuSatM5uiR3VFuxVpYnX-YdkbFXwaXydVd1jUzRh7Scu34jwWoQ3ynpVyi1SkB5kRJdo3xzV0e8YIpqe4I51wyp9Od6cqFQRB0-29nsrlMmw6jQJFr3x1YbkeO_sBqo6neQ09jw_YwFO_miJt-_p1Muz1OhFcQXhbJ0nsbgx5OlpgnrObwW58gr1QZaQ8dQyvR6Sz6ScJhlijlMocuy4JUOai-U3tf6u2nfVcdbz9bj1IhmrRqlwZ5grB99IrBtISSe7eUkzi_rjOvs_JUfayxFO7weBVhgjwzIQBBBOSV64r3SgF9pWqcremxvtq2e5eo4ri6w3VmqmzeRauVUhn3vsY6fkmS-U2695bIcFhZJB2vy_1dvxyce50zqsrwY-VoBy5lzvYgZkFsoMjkI8FiIlR6Ik0nMs1UWgFSgV5n3cWVhQ3w3cUy_51kZM_ffiJZBhxO3ci3kihJuMZbJ_eU4niovSQOhe8XQ1cxo5gXByeqs2-Vhpaflp92ZcoJmql5U-28tW4IV0tStav5mx6h60-J_R2Spz7ce-zSjnXnhlom2Iiy6-M52w4Sg50XubQgk0thrpaF88QowdiYQVnruh7ak37waY7iim0ZRrVve';  // Your OAuth2 token
    
    $headers = array(
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    );
    

        //Initializing curl to open a connection
        $ch = curl_init();
 
        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        //Now close the connection
        curl_close($ch);
        // print_r($result);
 
        //and return the result 
        return $result;
    }
}