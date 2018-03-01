<?php
    session_start();

    if(!isset($_SESSION['username'])) {
       header('location:login.php');
    }

    require('connection.php');
    require('chart_connection.php');

    try {
        $count = $conn->prepare("
            SELECT 
            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'techie') as techie, 
            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'accounting') as accounting, 
            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'it/noc') as it,
            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'pa') as pa,
            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'simpro') as simpro
             FROM timestamps LIMIT 1");
        $count->execute();
        $count = $count->fetch(PDO::FETCH_ASSOC);

        $techie = $count['techie'];
        $it = $count['it'];
        $accounting  = $count['accounting'];
        $pa = $count['pa'];
        $simpro = $count['simpro'];

       $user_count = $conn->prepare("SELECT (SELECT COUNT(*) FROM timestamps WHERE DATE(time_in) = DATE(NOW() - INTERVAL 30 DAY)) as month_ago, (SELECT COUNT(*) FROM timestamps WHERE DATE(time_in) = DATE(NOW() - INTERVAL 7 DAY)) as week_ago, (SELECT COUNT(*) FROM timestamps WHERE DATE(time_in) = DATE(NOW() - INTERVAL 1 DAY)) as day_ago, (SELECT COUNT(*) FROM timestamps WHERE DATE(time_in) = DATE(NOW() - INTERVAL 0 DAY)) as today FROM timestamps LIMIT 1");
        $user_count->execute();
        $user_count = $user_count->fetch(PDO::FETCH_ASSOC);

        $month_ago = $user_count['month_ago'];
        $week_ago = $user_count['week_ago'];
        $yesterday = $user_count['day_ago'];
        $today = $user_count['today'];
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
?>
﻿<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome Admin | Home</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"> -->
    <link href="css/icons/material-icons.css" rel="stylesheet">
    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />
    <!-- Morris Chart Css-->
    <link href="plugins/morrisjs/morris.css" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
</head>
<body class="theme-purple">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <?php include('loader.php'); ?>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <?php include('search_bar.php'); ?>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <?php include('top_bar.php'); ?>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <?php include('left_sidebar_user_info.php'); ?>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                        <?php include('left_sidebar_main.php'); ?>

                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <?php include('footer.php'); ?>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <?php include('right_sidebar.php'); ?>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2 class="flow-text">DASHBOARD</h2>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">web</i>
                    </div>
                    <div class="content">
                        <div class="text">TECHIE DEPT</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $techie; ?>" data-speed="1000" data-fresh-interval="1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">build</i>
                    </div>
                    <div class="content">
                        <div class="text">IT DEPT</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $it; ?>" data-speed="1000" data-fresh-interval="1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_balance</i>
                    </div>
                    <div class="content">
                        <div class="text">ACCOUNTING DEPT</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $accounting; ?>" data-speed="1000" data-fresh-interval="1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-blue hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">local_phone</i>
                    </div>
                    <div class="content">
                        <div class="text">PA DEPT</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $pa; ?>" data-speed="1000" data-fresh-interval="1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-indigo hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">settings_applications</i>
                    </div>
                    <div class="content">
                        <div class="text">SIMPRO</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $simpro; ?>" data-speed="1000" data-fresh-interval="1"></div>
                    </div>
                </div>
            </div>
            <!-- Task Info -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TASK INFOS</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover dashboard-task-infos" id="table">
                                <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>NAME</th>
                                        <th>DEPARTMENT</th>
                                        <th>TIME IN</th>
                                        <th>TIME OUT</th>
                                        <th>SHIFT STATUS</th>
                                    </tr>
                                </thead>
                                <!-- <tbody>
                                    <?php
                                        // $employees = $conn->prepare("SELECT u.id, u.name, t.status, TIME_FORMAT(t.time_in, '%m-%d-%Y at %h:%i %p') as time_in, TIME_FORMAT(t.time_out, '%h:%i %p') as time_out, u.dept FROM users u JOIN timestamps t ON u.id = t.employee_id WHERE t.time_in >= CURDATE()");
                                        // $employees->execute();

                                        // while ($row = $employees->fetch(PDO::FETCH_ASSOC)) {
                                        //    echo '
                                        //        <tr>
                                        //            <td>'.$row['id'].'</td>
                                        //            <td>'.$row['time_in'].'</td>
                                        //            <td>'.$row['name'].'</td>
                                        //            <td>'.$row['dept'].'</td>
                                        //            <td>'.$row['status'].' '.$row['time_out'].'</td>
                                        //        </tr>
                                        //    ';
                                        //}
                                    ?>
                            </tbody> -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Donut Chart -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TODAY'S ATTENDANCE</h2>
                    </div>
                    <div class="body">
                        <div id="donut_chart" class="graph"></div>
                    </div>
                </div>
            </div>
        <!-- #END# Donut Chart -->
        <!-- Visitors -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="body bg-pink">
                        <h3>Previous Attendance</h3>
                        <div class="sparkline" data-type="line" data-spot-Radius="4" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#fff"
                             data-min-Spot-Color="rgb(255,255,255)" data-max-Spot-Color="rgb(255,255,255)" data-spot-Color="rgb(255,255,255)"
                             data-offset="90" data-width="100%" data-height="92px" data-line-Width="2" data-line-Color="rgba(255,255,255,0.7)"
                             data-fill-Color="rgba(0, 188, 212, 0)">
                            <?php echo $month_ago . ',' . $week_ago . ',' . $yesterday . ',' . $today;?>
                        </div>
                        <ul class="dashboard-stat-list">
                            <li>
                                TODAY
                                <span class="pull-right"><b><?php echo $today; ?></b> <small>PRESENT</small></span>
                            </li>
                            <li>
                                YESTERDAY
                                <span class="pull-right"><b><?php echo $yesterday; ?></b> <small>PRESENT</small></span>
                            </li>
                            <li>
                                LAST WEEK
                                <span class="pull-right"><b><?php echo $week_ago; ?></b> <small>PRESENT</small></span>
                            </li>
                            <li>
                                LAST 30 DAYS
                                <span class="pull-right"><b><?php echo $month_ago; ?></b> <small>PRESENT</small></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #END# Visitors -->
    </section>
    <!-- Change pASS -->
<!-- <div class="modal modal-sm" id="exampleModal" style="height:55vh;background-color: white;color:grey;border-radius: 10px;">
        <div class="modal-header">
            <h3 >Change Password</h3>
        </div>
        <div class="modal-body">
            <form method="post" action="changepass.php">
              <div class="form-group">
                <label for="message-text" class="col-form-label">Current Password: </label>
                <input type="password" class="form-control" name="prevpass" placeholder="Password">
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">New Password: </label>
                <input type="password" class="form-control" name="nupass" placeholder="New password">
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Re-type password: </label>
                <input type="password" class="form-control" name="renupass" placeholder="Re-type password">
              </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:150px;">Close</button> &nbsp;
                <button type="submit" class="btn btn-primary" name="submit" style="width:150px;">OK</button>
            </form>
        </div>
    </div> -->
    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/morrisjs/morris.js"></script>
    <script>
        $(document).ready(function() {
            Morris.Donut({
                element: donut_chart,
                data:
                    <?php
                        $result = $db->query("SELECT 
                            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'techie') as techie, 
                            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'accounting') as accounting,
                            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'it/noc') as it,
                            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'pa') as pa,
                            (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'simpro') as simpro
                            FROM timestamps LIMIT 1");
                        if($result->num_rows > 0) {
                            while($row = $result->FETCH_ASSOC()) {
                                echo "[{
                                    label: 'Techie',
                                    value: '".$row['techie']."'
                                }, {
                                    label: 'IT',
                                    value: '".$row['it']."'
                                }, {
                                    label: 'Accounting',
                                    value: '".$row['accounting']."'
                                }, {
                                    label: 'PA',
                                    value: '".$row['accounting']."'
                                }, {
                                    label: 'Simpro',
                                    value: '".$row['simpro']."'
                                }
                            ],";
                            }
                        }
                        echo "\n";
                    ?>
                    colors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)'],
                formatter: function (y) {
                    return y
                }
            });
        });
    </script>
    <script type="text/javascript">
    $(document).ready(function(e) {
        var tab = $('#table').DataTable({
            paging: false,
            info: false,
            
        });     
                setInterval(function(){
                    $.get('getData.php', 
                        function(response){
                            var json = $.parseJSON(response);
                            // console.log(json);
                            tab.clear();
                            for(var x=0;x<json.length;x++) {
                                tab.row.add([json[x].id,json[x].name,json[x].dept,json[x].time_in,json[x].time_out,json[x].status]);
                            }
                            tab.draw();
                    });
                }, 1000);
    });
    </script>
    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="plugins/jquery-countto/jquery.countTo.js"></script>
    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>
    <script>
        $(".sparkline").each(function () {
            var $this = $(this);
            $this.sparkline('html', $this.data());
        });
	</script>    

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
