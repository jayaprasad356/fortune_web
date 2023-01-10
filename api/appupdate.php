<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
include_once('../includes/functions.php');
$fn = new functions;
$fn->monitorApi('appupdate');

$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
$old_device_id = (isset($_POST['device_id']) && $_POST['device_id'] != "") ? $db->escapeString($_POST['device_id']) : "";
$user_id = (isset($_POST['user_id']) && $_POST['user_id'] != "") ? $db->escapeString($_POST['user_id']) : "";
$fcm_id = (isset($_POST['fcm_id']) && $_POST['fcm_id'] != "") ? $db->escapeString($_POST['fcm_id']) : "";
$sql = "SELECT * FROM settings";
$db->sql($sql);
$set = $db->getResult();
$res = array();
if($user_id != ''){
    $sql = "SELECT code_generate_time,total_referrals,withdrawal,last_updated,device_id,datediff('$date', joined_date) AS history_days,datediff('$datetime', last_updated) AS days,code_generate,withdrawal_status,status,joined_date  FROM users WHERE id = $user_id ";
    $db->sql($sql);
    $res = $db->getResult();
    $history_days = $res[0]['history_days'];
    $device_id = $res[0]['device_id'];

    if(!empty($fcm_id)){
        $sql = "UPDATE `users` SET  `fcm_id` = '$fcm_id' WHERE `id` = $user_id";
        $db->sql($sql);
    
    }
    if(isset($_POST['device_id']) && ($device_id != $old_device_id)){
        $sql = "UPDATE `users` SET  `status` = 2 WHERE `id` = $user_id";
        $db->sql($sql);

    }

    if($history_days > $set[0]['duration']){
        $sql = "UPDATE `users` SET  `code_generate` = 0 WHERE `id` = $user_id";
        $db->sql($sql);

    }

    $days = $res[0]['days'];
    if($days != 0){
        $sql = "UPDATE `users` SET  `today_codes` = 0,`last_updated` = '$datetime' WHERE `id` = $user_id";
        $db->sql($sql);

    }
}
$sql = "SELECT * FROM app_settings";
$db->sql($sql);
$appres = $db->getResult();
$response['success'] = true;
$response['message'] = "App Update listed Successfully";
$response['data'] = $appres;
$response['settings'] = $set;
$response['user_details'] = $res;
print_r(json_encode($response));

?>