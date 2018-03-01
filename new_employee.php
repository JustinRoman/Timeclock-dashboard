<?php
    session_start();

    if(!isset($_SESSION['username'])) {
       header('location:login.php');
    }

    if(!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != 0){
        header('location:page_403.html');
    }


    require('connection.php');
    require('api.php');

    try {
        $administrator_list = $conn->prepare("SELECT id, name, email, dept, office FROM users ORDER BY dept");
        $administrator_list->execute();
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

    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet" .>

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
            <div class="card">
                <div class="header">
                    <h2>New Employee Form</h2>
                </div>
                <div class="body">
                    <form method="post">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="name">Full Name</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="f_name" class="form-control" placeholder="Enter your first name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="name">Username</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="name">Password</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="name">Please Re-type Your Password</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" name="repassword" class="form-control" placeholder="Re-type your password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name">E-mail</label>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label for="dept">Department</label>
                                <div class="form-group form-float">
                                    <select class="form-control show-tick" name="dept" required>
                                       <option value="Accounting">ACCOUNTING</option>  
                                       <option value="IT/NOC">IT</option>
                                       <option value="PA">PA</option>
                                       <option value="Simpro">SIMPRO</option>
                                       <option value="Techie">TECHIE</option>
                                   </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label for="dept">Office</label>
                                <div class="form-group form-float">
                                    <select class="form-control show-tick" name="office" required>
                                       <option value="PH">PH - PHILIPPINES</option>
                                       <option value="FJ">FJ - FIJI</option>
                                       <option value="SIMPRO">Simpro</option>
                                   </select>
                                </div>
                            </div>
                         </div>
                         <div class="row clearfix">
                            <div class="col-lg-offset-5 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                <button type="submit" name="submit_newemp" class="btn btn-success m-t-15 waves-effect">CONFIRM</button>
                                <button type="reset" class="btn btn-danger bg-red m-t-15 waves-effect">RESET</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
    <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/morrisjs/morris.js"></script>
    <!-- ChartJs -->
    <script src="plugins/chartjs/Chart.bundle.js"></script>
    <!-- Flot Charts Plugin Js -->
    <script src="plugins/flot-charts/jquery.flot.js"></script>
    <script src="plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="plugins/flot-charts/jquery.flot.time.js"></script>
    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>
    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/index.js"></script>
    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
