<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $user = $res[0];
    $joined_date = $user['joined_date'];
    $today_date = date('Y-m-d');

    if ($joined_date) {
        $joined = new DateTime($joined_date);
        $today = new DateTime($today_date);
        $interval = $joined->diff($today);
        $total_days = $interval->days + 1;

        $sql_leaves = "SELECT COUNT(*) AS leave_count FROM leaves WHERE user_id = '$user_id' AND date >= '$joined_date' AND date <= '$today_date'";
        $db->sql($sql_leaves);
        $res_leaves = $db->getResult();
        $leave_count = $res_leaves[0]['leave_count'] ?? 0;

        $worked_days = $total_days - $leave_count;
    } else {
        $worked_days = 0;
    }

    $user['worked_days'] = $worked_days;

    $response['success'] = true;
    $response['message'] = "Users listed Successfully";
    $response['data'] = $user;
    print_r(json_encode($response));

} else {
    $response['success'] = false;
    $response['message'] = "No Users Found";
    print_r(json_encode($response));
}
?>
