<?php session_start();

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$function = new custom_functions;

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;
// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}
// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}
// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;
include "header.php";
if ($_SESSION['role'] == 'Super Admin') {
    $joinCondition = "WHERE ID is NOT NULL";
    $referCodePattern = "";
} else {
    $referCode = $_SESSION['refer_code'];
    $joinCondition = "WHERE refer_code REGEXP '^$referCode'";
    $referCodePattern = "^$referCode";
}

// Fetch user count
$sql = "SELECT COUNT(*) AS userCount FROM users $joinCondition";
$db->sql($sql);
$res = $db->getResult();
$userCount = (isset($res[0]['userCount'])) ? $res[0]['userCount'] : 0;
// Fetch active user count
$sql = "SELECT COUNT(*) AS activeUserCount FROM users $joinCondition AND status = 1 AND code_generate = 1 AND today_codes != 0";
$db->sql($sql);
$res = $db->getResult();
$activeUserCount = (isset($res[0]['activeUserCount'])) ? $res[0]['activeUserCount'] : 0;
// Fetch today's registration count
$currentDate = date('Y-m-d');
$sql = "SELECT COUNT(*) AS todayRegistrationCount FROM users $joinCondition AND joined_date = '$currentDate' AND status = 1";
$db->sql($sql);
$res = $db->getResult();
$todayRegistrationCount = (isset($res[0]['todayRegistrationCount'])) ? $res[0]['todayRegistrationCount'] : 0;

// Fetch unpaid withdrawals amount
$sql = "SELECT SUM(w.amount) AS unpaidWithdrawalsAmount FROM withdrawals w INNER JOIN users u ON u.id = w.user_id WHERE u.refer_code REGEXP '$referCodePattern' AND w.status = 0";
$db->sql($sql);
$res = $db->getResult();
$unpaidWithdrawalsAmount = "Rs." . (isset($res[0]['unpaidWithdrawalsAmount'])) ? $res[0]['unpaidWithdrawalsAmount'] : 0;
// Fetch paid withdrawals amount
$sql = "SELECT SUM(w.amount) AS paidWithdrawalsAmount FROM withdrawals w INNER JOIN users u ON u.id = w.user_id WHERE u.refer_code REGEXP '$referCodePattern' AND w.status = 1";
$db->sql($sql);
$res = $db->getResult();
$paidWithdrawalsAmount = "Rs." . (isset($res[0]['paidWithdrawalsAmount'])) ? $res[0]['paidWithdrawalsAmount'] : 0;
// Fetch total transactions amount
$sql = "SELECT SUM(t.amount) AS totalTransactionsAmount FROM transactions t INNER JOIN users u ON u.id = t.user_id WHERE u.refer_code REGEXP '$referCodePattern'";
$db->sql($sql);
$res = $db->getResult();
$totalTransactionsAmount = "Rs." . (isset($res[0]['totalTransactionsAmount'])) ? $res[0]['totalTransactionsAmount'] : 0;


?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Fortune - Dashboard</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Home</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                </li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                        <h3><?php
                            echo $activeUserCount;
                             ?></h3>
                            <p>Active Users</p>
                        </div>
                        <div class="icon"><i class="fa fa-user"></i></div>
                        <a href="users.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Top Today Coders <small>( Day: <?= date("D"); ?>)</small></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="table-responsive">
                                <table class="table no-margin" id='top_seller_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=top_coders" data-page-list="[5, 10, 20, 50, 100, 200,500]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams_top_seller" data-sort-name="today_codes" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                                "fileName": "Yellow app-withdrawals-list-<?= date('d-m-Y') ?>",
                                "ignoreColumn": ["operate"] 
                            }'>
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable='true'>ID</th>
                                            <!-- <th data-field="joined_date" data-visible="true">Joined Date</th> -->
                                            <th data-field="name" data-sortable='true'>Name</th>
                                            <th data-field="mobile">Mobile</th>
                                            <th data-field="today_codes" data-sortable='true'>Codes</th>
                                            
                                            <th data-field="earn" >Earn</th>
                                           
                                            <th data-field="l_referral_count" data-sortable='true'>Level Referals Count</th>
                                            <th data-field="level" data-sortable='true'>Level</th>
                                        
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Top Categories <small> ( Month: <?= date("M"); ?>) </small></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="table-responsive">
                                <table class="table no-margin" id='top_seller_table' data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=top_categories" data-page-list="[5,10]" data-page-size="5" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-sort-name="total_revenues" data-sort-order="desc" data-toolbar="#toolbar" data-query-params="queryParams_top_cat">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable='true'>Rank</th>
                                            <th data-field="cat_name" data-sortable='true' data-visible="true">Category</th>
                                            <th data-field="p_name" data-sortable='true' data-visible="true">Product Name</th>
                                            <th data-field="total_revenues" data-sortable='true'>Total Revenue(<?= $settings['currency'] ?>)</th>
                                            <th data-field="operate">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div> -->
            </div>
        </section>
    </div>
    <script>
        $('#filter_order').on('change', function() {
            $('#orders_table').bootstrapTable('refresh');
        });
        $('#seller_id').on('change', function() {
            $('#orders_table').bootstrapTable('refresh');
        });
    </script>
    <script>
        function queryParams(p) {
            return {
                "filter_order": $('#filter_order').val(),
                "seller_id": $('#seller_id').val(),
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                search: p.search
            };
        }

    </script>
    <?php include "footer.php"; ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Hour', 'Total - <?= $stu_total[0]['total'] ?>'],
                <?php foreach ($result_order as $row) {
                    //$date = date('d-M', strtotime($row['order_date']));
                    echo "['" . $row['time'] . "'," . $row['numoft'] . "],";
                } ?>
            ]);
            var options = {
                chart: {
                    title: 'Transactions By Hour Wise',
                    //subtitle: 'Total Sale In Last Week (Month: <?php echo date("M"); ?>)',
                }
            };

            var chart = new google.charts.Bar(document.getElementById('earning_chart'));
            chart.draw(data, google.charts.Bar.convertOptions(options));


            var data = google.visualization.arrayToDataTable([
                ['Hour', 'Total - <?= $stu_total2[0]['total'] .'\n₹'.$stu_total2[0]['total'] * COST_PER_CODE ?>'],
                <?php foreach ($result_order2 as $row) {
                    //$date = date('d-M', strtotime($row['order_date']));
                    echo "['" . $row['time'] . "'," . $row['codes'] . "],";
                } ?>
            ]);
            var options = {
                chart: {
                    title: 'Codes By Hour Wise',
                    //subtitle: 'Total Sale In Last Week (Month: <?php echo date("M"); ?>)',
                }
            };

            var chart = new google.charts.Bar(document.getElementById('earning_chart2'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
</body>
</html>