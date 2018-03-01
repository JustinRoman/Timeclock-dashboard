<?php
    session_start();

    if(!isset($_SESSION['username'])) {
       header('location:login.php');
    }

    require('connection.php');
    require('chart_connection.php');
    require('api.php');

    try {
        $count = $conn->prepare("SELECT (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'techie') as techie, (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'marketing') as marketing, (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'noc') as noc, (SELECT COUNT(*) FROM timestamps WHERE DATE(`time_in`) = CURDATE() AND dept = 'it') as it FROM timestamps LIMIT 1");
        $count->execute();
        $count = $count->fetch(PDO::FETCH_ASSOC);

        $techie = $count['techie'];
        $noc = $count['noc'];
        $it = $count['it'];
        $marketing  = $count['marketing'];

        if (isset($_POST['view_time'])) {
			$employee_id = $_POST['e_id'];
		}

    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    function char_at($str, $pos) {
        return $str{$pos};
    }
?>
﻿<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome Admin | Timesheets</title>
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
        <form method="post" action="time_keeper_view.php">
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <label for="e_id">EMPLOYEE NAME</label>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input list="names" type="text" name="e_id" class="form-control" 
                                placeholder="&nbsp;&nbsp;ENTER EMPLOYEE NAME" required>
                                <datalist id="names">
                                  <?php
                                    $names = $conn->prepare("SELECT name FROM users");
                                    $names->execute();

                                    while($row = $names->fetch(PDO::FETCH_ASSOC)) {
                                        echo '
                                            <option value="'.$row['name'].'">'.$row['name'].'</option>
                                        ';
                                    }
                                  ?>
                                </datalist>
                            </div>
                        </div>
                </div>
                <button style="margin-top: 26px;" type="submit" name="view_time" class="btn btn-success m-t-15 waves-effect">VIEW TIMESHEET</button>
            </div>
        </form>
    </section>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
<!--     <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/morrisjs/morris.js"></script> -->

    <!-- ChartJs -->
    <script src="plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
<!--     <script src="plugins/flot-charts/jquery.flot.js"></script>
    <script src="plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="plugins/flot-charts/jquery.flot.time.js"></script> -->

    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/custom.js"></script>


</body>

</html>
