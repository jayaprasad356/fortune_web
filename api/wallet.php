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
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/functions.php');
$fn = new functions;
$fn->monitorApi('wallet');

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$codes = (isset($_POST['codes']) && $_POST['codes'] != "") ? $db->escapeString($_POST['codes']) : 0;
$datetime = date('Y-m-d H:i:s');
$type = 'generate';
if($codes != 0){
    $amount = $codes * COST_PER_CODE;
    $sql = "INSERT INTO transactions (`user_id`,`codes`,`amount`,`datetime`,`type`)VALUES('$user_id','$codes','$amount','$datetime','$type')";
    $db->sql($sql);
    $res = $db->getResult();

    $sql = "UPDATE `users` SET  `today_codes` = today_codes + $codes,`total_codes` = total_codes + $codes,`earn` = earn + $amount,`balance` = balance + $amount WHERE `id` = $user_id";
    $db->sql($sql);
}

$sql = "SELECT today_codes,total_codes,balance,code_generate,status  FROM users WHERE id = $user_id ";
$db->sql($sql);
$res = $db->getResult();

$response['success'] = true;
$response['message'] = "Code Added Successfully";
$response['status'] = $res[0]['status'];
$response['balance'] = $res[0]['balance'];
$response['today_codes'] = $res[0]['today_codes'];
$response['total_codes'] = $res[0]['total_codes'];
$response['code_generate'] = $res[0]['code_generate'];
$response['status'] = $res[0]['status'];
print_r(json_encode($response));


?>