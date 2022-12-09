<?php
session_start();
// include_once('../api-firebase/send-email.php');
include('../includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/functions.php');
$function = new functions;



if (isset($_POST['bulk_upload']) && $_POST['bulk_upload'] == 1) {
    $count = 0;
    $count1 = 0;
    $error = false;
    $filename = $_FILES["upload_file"]["tmp_name"];
    $result = $fn->validate_image($_FILES["upload_file"], false);
    if (!$result) {
        $error = true;
    }
    if ($_FILES["upload_file"]["size"] > 0  && $error == false) {
        $file = fopen($filename, "r");
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // print_r($emapData);
            if ($count1 != 0) {
                $emapData[0] = trim($db->escapeString($emapData[0]));
                $emapData[1] = trim($db->escapeString($emapData[1]));          
                $emapData[2] = trim($db->escapeString($emapData[2]));
                $emapData[3] = trim($db->escapeString($emapData[3]));
                $emapData[4] = trim($db->escapeString($emapData[4]));
                $emapData[5] = trim($db->escapeString($emapData[5]));
                $emapData[6] = trim($db->escapeString($emapData[6]));
                $emapData[7] = trim($db->escapeString($emapData[7]));
                $emapData[8] = trim($db->escapeString($emapData[8]));
                $emapData[9] = trim($db->escapeString($emapData[9]));
                $emapData[10] = trim($db->escapeString($emapData[10]));
                $emapData[11] = trim($db->escapeString($emapData[11]));
                $emapData[12] = trim($db->escapeString($emapData[12]));
                $emapData[13] = trim($db->escapeString($emapData[13]));
                $emapData[14] = trim($db->escapeString($emapData[14]));
                $emapData[15] = trim($db->escapeString($emapData[15]));
                $emapData[16] = trim($db->escapeString($emapData[16]));
                $emapData[17] = trim($db->escapeString($emapData[17]));
                $emapData[18] = trim($db->escapeString($emapData[18]));
                $emapData[19] = trim($db->escapeString($emapData[19]));
                $emapData[20] = trim($db->escapeString($emapData[20]));
                $emapData[21] = trim($db->escapeString($emapData[21]));   
                
                $sql = "SELECT id FROM users WHERE mobile = '$emapData[1]'";
                $db->sql($sql);
                $res = $db->getResult();
                $num = $db->numRows($res);
                if ($num >= 1) {
                    echo "<p class='alert alert-danger'>Mobile Number Already Exist</p><br>";
                    return false;

                }
            }

            $count1++;
        }
        fclose($file);
        $file = fopen($filename, "r");
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // print_r($emapData);
            if ($count1 != 0) {
                $emapData[0] = trim($db->escapeString($emapData[0]));
                $emapData[1] = trim($db->escapeString($emapData[1]));          
                $emapData[2] = trim($db->escapeString($emapData[2]));
                $emapData[3] = trim($db->escapeString($emapData[3]));
                $emapData[4] = trim($db->escapeString($emapData[4]));
                $emapData[5] = trim($db->escapeString($emapData[5]));
                $emapData[6] = trim($db->escapeString($emapData[6]));
                $emapData[7] = trim($db->escapeString($emapData[7]));
                $emapData[8] = trim($db->escapeString($emapData[8]));
                $emapData[9] = trim($db->escapeString($emapData[9]));
                $emapData[10] = trim($db->escapeString($emapData[10]));
                $emapData[11] = trim($db->escapeString($emapData[11]));
                $emapData[12] = trim($db->escapeString($emapData[12]));
                $emapData[13] = trim($db->escapeString($emapData[13]));
                $emapData[14] = trim($db->escapeString($emapData[14]));
                $emapData[15] = trim($db->escapeString($emapData[15]));
                $emapData[16] = trim($db->escapeString($emapData[16]));
                $emapData[17] = trim($db->escapeString($emapData[17]));
                $emapData[18] = trim($db->escapeString($emapData[18]));
                $emapData[19] = trim($db->escapeString($emapData[19]));
                $emapData[20] = trim($db->escapeString($emapData[20]));
                $emapData[21] = trim($db->escapeString($emapData[21])); 
                $emapData[22] = trim($db->escapeString($emapData[22]));  
                $emapData[23] = trim($db->escapeString($emapData[23]));    
                $data = array(
                    'name'=>$emapData[0],
                    'mobile'=>$emapData[1],
                    'password' => $emapData[2],
                    'dob' => $emapData[3],
                    'email' => $emapData[4],
                    'city' => $emapData[5],
                    'referred_by' => $emapData[6],
                    'earn' => $emapData[7],
                    'withdrawal' => $emapData[8],
                    'withdrawal_status' => $emapData[9],
                    'total_referrals' => $emapData[10],
                    'today_codes' => $emapData[11],
                    'total_codes' => $emapData[12],
                    'balance' => $emapData[13],
                     'device_id' => $emapData[14],
                     'status' => $emapData[15],
                     'refer_code' => $emapData[16],
                     'refer_bonus_sent' => $emapData[17],
                     'register_bonus_sent'=> $emapData[18],
                     'code_generate' => $emapData[19],
                    'code_generate_time' => $emapData[20],
                    'fcm_id' => $emapData[21],
                    'last_updated' => $emapData[22],
                    'joined_date' => $emapData[23],
                  
                                 
                );
                $db->insert('users', $data);

            }

            $count1++;
        }
        fclose($file);

        echo "<p class='alert alert-success'>CSV file is successfully imported!</p><br>";
    } else {
        echo "<p class='alert alert-danger'>Invalid file format! Please upload data in CSV file!</p><br>";
    }
}


if (isset($_POST['bulk_update']) && $_POST['bulk_update'] == 1) {
    $count = 0;
    $count1 = 0;
    $error = false;
    $filename = $_FILES["upload_file"]["tmp_name"];
    $result = $fn->validate_image($_FILES["upload_file"], false);
    if (!$result) {
        $error = true;
    }
    if ($_FILES["upload_file"]["size"] > 0  && $error == false) {
        $file = fopen($filename, "r");
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // print_r($emapData);
            if ($count1 != 0) {
                $emapData[0] = trim($db->escapeString($emapData[0]));
                $emapData[1] = trim($db->escapeString($emapData[1]));          
                $emapData[2] = trim($db->escapeString($emapData[2]));
                $emapData[3] = trim($db->escapeString($emapData[3]));
                $emapData[4] = trim($db->escapeString($emapData[4]));
                $emapData[5] = trim($db->escapeString($emapData[5]));
                $emapData[6] = trim($db->escapeString($emapData[6]));
                $emapData[7] = trim($db->escapeString($emapData[7]));
                $emapData[8] = trim($db->escapeString($emapData[8]));
                $emapData[9] = trim($db->escapeString($emapData[9]));
                $emapData[10] = trim($db->escapeString($emapData[10]));
                $emapData[11] = trim($db->escapeString($emapData[11]));
                $emapData[12] = trim($db->escapeString($emapData[12]));
                $emapData[13] = trim($db->escapeString($emapData[13]));
                $emapData[14] = trim($db->escapeString($emapData[14]));
                $emapData[15] = trim($db->escapeString($emapData[15]));
                $emapData[16] = trim($db->escapeString($emapData[16]));
                $emapData[17] = trim($db->escapeString($emapData[17]));
                $emapData[18] = trim($db->escapeString($emapData[18]));
                $emapData[19] = trim($db->escapeString($emapData[19]));
                $emapData[20] = trim($db->escapeString($emapData[20]));
                $emapData[21] = trim($db->escapeString($emapData[21]));   
                $emapData[22] = trim($db->escapeString($emapData[22]));  
                $emapData[23] = trim($db->escapeString($emapData[23]));   
                // $data = array(
                //     'name'=>$emapData[0],
                //     'mobile'=>$emapData[1],
                //     'password' => $emapData[2],
                //     'dob' => $emapData[3],
                //     'email' => $emapData[4],
                //     'city' => $emapData[5],
                //     'referred_by' => $emapData[6],
                //     'earn' => $emapData[7],
                //     'withdrawal' => $emapData[8],
                //     'withdrawal_status' => $emapData[9],
                //     'total_referrals' => $emapData[10],
                //     'today_codes' => $emapData[11],
                //     'total_codes' => $emapData[12],
                //     'balance' => $emapData[13],
                //      'device_id' => $emapData[14],
                //      'status' => $emapData[15],
                //      'refer_code' => $emapData[16],
                //      'refer_bonus_sent' => $emapData[17],
                //      'register_bonus_sent'=> $emapData[18],
                //      'code_generate' => $emapData[19],
                //     'code_generate_time' => $emapData[20],
                //     'fcm_id' => $emapData[21],
                //     'last_updated' => $emapData[22],
                //     'joined_date' => $emapData[23],
                  
                                 
                // );
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$emapData[23])) {
                    $sql = "UPDATE users SET `joined_date`='$emapData[23]' WHERE mobile= '$emapData[1]'";
                    $db->sql($sql);
                }


            }

            $count1++;
        }
        fclose($file);

        echo "<p class='alert alert-success'>CSV file is updated successfully!</p><br>";
    } else {
        echo "<p class='alert alert-danger'>Invalid file format! Please upload data in CSV file!</p><br>";
    }
}


if (isset($_POST['delete_variant'])) {
    $v_id = $db->escapeString(($_POST['id']));
    $sql = "DELETE FROM product_variant WHERE id = $v_id";
    $db->sql($sql);
    $result = $db->getResult();
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}
if (isset($_POST['referred_by_code_change'])) {
    $user_id = $db->escapeString($fn->xss_clean($_POST['user_id']));
    $sql = "SELECT * FROM users WHERE id=" . $user_id;
    $db->sql($sql);
    $res = $db->getResult();
    if (!empty($res)) {
        $referred_by = $res[0]['referred_by'];
        echo $referred_by;
    } else {
        echo "";
    }

}