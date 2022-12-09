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


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$amount = $db->escapeString($_POST['amount']);


$sql = "SELECT * FROM settings";
$db->sql($sql);
$mres = $db->getResult();
$main_ws = $mres[0]['withdrawal_status'];
$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$res = $db->getResult();
$balance = $res[0]['balance'];
$withdrawal_status = $res[0]['withdrawal_status'];
$datetime = date('Y-m-d H:i:s');
$sql = "SELECT * FROM bank_details WHERE user_id = $user_id ";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if($withdrawal_status == 1 &&  $main_ws == 1 ){
    if ($num >= 1) {
        if($balance >= 250){
            if($balance >= $amount){
                $sql = "UPDATE `users` SET `balance` = balance - $amount,`withdrawal` = withdrawal + $amount WHERE `id` = $user_id";
                $db->sql($sql);
                $sql = "INSERT INTO withdrawals (`user_id`,`amount`,`datetime`)VALUES('$user_id','$amount','$datetime')";
                $db->sql($sql);
                $sql = "SELECT * FROM users WHERE id = $user_id ";
                $db->sql($sql);
                $res = $db->getResult();
                $balance = $res[0]['balance'];
                $response['success'] = true;
                $response['balance'] = $balance;
                $response['message'] = "Withdrawal Requested Successfully";
                print_r(json_encode($response));
        
            }
            else{
                $response['success'] = false;
                $response['message'] = "Insufficent Balance";
                print_r(json_encode($response)); 
            }
        
        }
        else{
            $response['success'] = false;
            $response['message'] = "Required Minimum Amount to Withdrawal";
            print_r(json_encode($response)); 
        }
    }else{
        $response['success'] = false;
        $response['message'] = "Update Bank Details first";
        print_r(json_encode($response)); 
    
    }
}else{
    $response['success'] = false;
    $response['message'] = "Withdrawal restricted for your account";
    print_r(json_encode($response));    
}






?>