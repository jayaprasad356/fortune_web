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
$sql = "SELECT code_generate,num_sync_times,sync_codes FROM settings";
$db->sql($sql);
$set = $db->getResult();
$code_generate = $set[0]['code_generate'];
$sync_codes = $set[0]['sync_codes'];
if($code_generate == 1){
    if($codes != 0){
        if($codes <= $sync_codes){
            $currentdate = date('Y-m-d');
            $amount = $codes * COST_PER_CODE;
            $sql = "SELECT COUNT(id) AS count  FROM transactions WHERE user_id = $user_id AND DATE(datetime) = '$currentdate'";
            $db->sql($sql);
            $tres = $db->getResult();
            $t_count = $tres[0]['count'];
            if ($t_count > $set[0]['num_sync_times']) {
                $response['success'] = false;
                $response['message'] = "You Reached Daily Sync Limit";
                print_r(json_encode($response));
                return false;
            }
    
            $sql = "INSERT INTO transactions (`user_id`,`codes`,`amount`,`datetime`,`type`)VALUES('$user_id','$codes','$amount','$datetime','$type')";
            $db->sql($sql);
            $res = $db->getResult();
        
            $sql = "UPDATE `users` SET  `today_codes` = today_codes + $codes,`total_codes` = total_codes + $codes,`earn` = earn + $amount,`balance` = balance + $amount,`last_updated` = '$datetime' WHERE `id` = $user_id";
            $db->sql($sql);
            $mentiondate = '2023-03-13';
    
            $sql = "SELECT referred_by  FROM users WHERE id = $user_id AND `joined_date` >= '$mentiondate' AND status = 1";
            $db->sql($sql);
            $res = $db->getResult();
            $num = $db->numRows($res);
            
        
            if($num == 1){
                $referred_by = $res[0]['referred_by'];
            
                
                $referamtcode = $codes * REFER_COST_PER_CODE;
                
                $sql = "SELECT id,mobile FROM users WHERE `refer_code` = '$referred_by' ";
                $db->sql($sql);
                $rep= $db->getResult();
                $sql = "UPDATE `users` SET  `sync_refer_wallet` = sync_refer_wallet + $referamtcode WHERE `refer_code` = '$referred_by'";
                $db->sql($sql);
                $response['sync'] = "Code Sync Successfully";
        
            }
        }
    }
     
    
    $sql = "SELECT today_codes,total_codes,balance,code_generate,status,referred_by FROM users WHERE id = $user_id ";
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
}
else{
  
    $response['success'] = false;
    $response['message'] = "Cannot Sync Right Now, Code Generate is turned off";
    print_r(json_encode($response));
}



?>