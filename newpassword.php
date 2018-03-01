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
    <title>Welcome Admin | Office Memos</title>

    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"> -->
    <link href="css/icons/material-icons.css" rel="stylesheet">
    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />

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
            <!-- Horizontal Layout -->
                <div class="row clearfix">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>CHANGE PASSWORD<small>FILL UP FORM TO CHANGE PASSWORD</small></h2>
                            </div>
                            <div class="body">
                                <div class="container container-fluid"> 
                                      <form class="form-horizontal" method="post" action="changepass.php">
                                  <div class="col-lg-6" class="form-group">
                                    <label for="message-text" class="col-form-label">Current Password: </label>
                                    <input type="password" class="form-control" name="prevpass" placeholder="Password" required>
                                    <br/>
                                  
                                    <label for="message-text" class="col-form-label">New Password: </label>
                                    <input type="password" class="form-control" name="nupass" placeholder="New password" required>
                                    <br/>
                                
                                    <label for="message-text" class="col-form-label">Re-type password: </label>
                                    <input type="password" class="form-control" name="renupass" placeholder="Re-type password" required>
                                    <br/>
                                    <button type="submit" class="btn btn-primary" name="submit" style="width:150px;">OK</button>
                                  </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Horizontal Layout -->
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

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/index.js"></script>
    <script src="js/pages/ui/dialogs.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
