<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnUpdate'])) {

    $code_generate = $db->escapeString(($_POST['code_generate']));
    $withdrawal_status = $db->escapeString(($_POST['withdrawal_status']));
    $sync_time = $db->escapeString(($_POST['sync_time']));
    $duration = $db->escapeString(($_POST['duration']));
    $payment_link = $db->escapeString(($_POST['payment_link']));
    $min_withdrawal = $db->escapeString(($_POST['min_withdrawal']));
    $job_details_link = $db->escapeString(($_POST['job_details_link']));
    $whatsapp = $db->escapeString(($_POST['whatsapp']));
    $chat_support = $db->escapeString(($_POST['chat_support']));
    $reward = $db->escapeString(($_POST['reward']));
    $ad_show_time = $db->escapeString(($_POST['ad_show_time']));
    $ad_status = $db->escapeString(($_POST['ad_status']));
    $fetch_time = $db->escapeString(($_POST['fetch_time']));
    $sync_codes = $db->escapeString(($_POST['sync_codes']));
    $error = array();
    $sql_query = "UPDATE settings SET code_generate=$code_generate,withdrawal_status=$withdrawal_status,sync_time=$sync_time,duration='$duration',payment_link = '$payment_link',min_withdrawal = $min_withdrawal,job_details_link = '$job_details_link',whatsapp = '$whatsapp',chat_support = $chat_support,reward = $reward,ad_show_time = $ad_show_time,ad_status = $ad_status,fetch_time = $fetch_time,sync_codes = $sync_codes WHERE id=1";
    $db->sql($sql_query);
    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        
        $error['update'] = "<section class='content-header'>
                                        <span class='label label-success'>Settings Updated Successfully</span> </section>";
    } else {
        $error['update'] = " <span class='label label-danger'>Failed</span>";
    }
    }

    // create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM settings WHERE id = 1";
$db->sql($sql_query);
$res = $db->getResult();
?>
<section class="content-header">
    <h1>Settings</h1>
    <?php echo isset($error['update']) ? $error['update'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-8">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="delivery_charge" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Code Generate</label><br>
                                        <input type="checkbox" id="code_generate_button" class="js-switch" <?= isset($res[0]['code_generate']) && $res[0]['code_generate'] == 1 ? 'checked' : '' ?>>
                                        <input type="hidden" id="code_generate_status" name="code_generate" value="<?= isset($res[0]['code_generate']) && $res[0]['code_generate'] == 1 ? 1 : 0 ?>">
                                    </div>

                                </div>
                            </div>
                           <br>
                           <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Withdrawal Status</label><br>
                                        <input type="checkbox" id="withdrawal_button" class="js-switch" <?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 'checked' : '' ?>>
                                        <input type="hidden" id="withdrawal_status" name="withdrawal_status" value="<?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 1 : 0 ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Chat Support</label><br>
                                        <input type="checkbox" id="chat_button" class="js-switch" <?= isset($res[0]['chat_support']) && $res[0]['chat_support'] == 1 ? 'checked' : '' ?>>
                                        <input type="hidden" id="chat_support" name="chat_support" value="<?= isset($res[0]['chat_support']) && $res[0]['chat_support'] == 1 ? 1 : 0 ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Ad Status</label><br>
                                        <input type="checkbox" id="ad_button" class="js-switch" <?= isset($res[0]['ad_status']) && $res[0]['ad_status'] == 1 ? 'checked' : '' ?>>
                                        <input type="hidden" id="ad_status" name="ad_status" value="<?= isset($res[0]['ad_status']) && $res[0]['ad_status'] == 1 ? 1 : 0 ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Sync Time(min)</label><br>
                                        <input type="number"class="form-control" name="sync_time" value="<?= $res[0]['sync_time'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Duration <small>(days)</small></label><br>
                                        <input type="number"class="form-control" name="duration" value="<?= $res[0]['duration'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Minimum Withdrawal</label><br>
                                        <input type="number"class="form-control" name="min_withdrawal" value="<?= $res[0]['min_withdrawal'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Whatsapp number</label><br>
                                        <input type="number"class="form-control" name="whatsapp" value="<?= $res[0]['whatsapp'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Code Rewards</label><br>
                                        <input type="number"class="form-control" name="reward" value="<?= $res[0]['reward'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Ad Show Time(min)</label><br>
                                        <input type="number"class="form-control" name="ad_show_time" value="<?= $res[0]['ad_show_time'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fetch Time(sec)</label><br>
                                        <input type="number"class="form-control" name="fetch_time" value="<?= $res[0]['fetch_time'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Sync Codes</label><br>
                                        <input type="number"class="form-control" name="sync_codes" value="<?= $res[0]['sync_codes'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Payment Link</label><br>
                                        <input type="link"class="form-control" name="payment_link" value="<?= $res[0]['payment_link'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Job Details Link</label><br>
                                        <input type="link"class="form-control" name="job_details_link" value="<?= $res[0]['job_details_link'] ?>">
                                    </div>
                                </div>
                            </div>
                           
                    </div>
                  
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>

<?php $db->disconnect(); ?>

<script>
    var changeCheckbox = document.querySelector('#code_generate_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#code_generate_status').val(1);

        } else {
            $('#code_generate_status').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#withdrawal_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#withdrawal_status').val(1);

        } else {
            $('#withdrawal_status').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#chat_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#chat_support').val(1);

        } else {
            $('#chat_support').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#ad_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#ad_status').val(1);

        } else {
            $('#ad_status').val(0);
        }
    };
</script>