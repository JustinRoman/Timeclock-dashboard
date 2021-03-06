<?php
    session_start();

    if(!isset($_SESSION['username'])) {
       header('location:login.php');
    }

    if(!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != 0){
        header('location:page_403.html');
    }

    require('connection.php');

    // TECHIE IT NOC MARKETING
    function verify_dept($dept) {
        if ($dept != "Techie" || $dept != "IT" || $dept != "NOC" || $dept != "Marketing") {
            return "<span>*Invalid Department</span>";
        } else {
            return $dept;
        }
    }

?>
﻿<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome Admin | New administrator</title>

    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"> -->

    <link href="css/icons/material-icons.css" rel="stylesheet">
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

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
    <!-- <div class="page-loader-wrapper">
        <?php include('loader.php'); ?>
    </div> -->
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
            <?php
                if(isset($_GET['action']) && $_GET['action']=='add') {
                    $id = $_GET['id'];

                    $temp = $conn->prepare("SELECT user_type FROM users WHERE id = ?");
                    $temp->execute([$id]);
                    $temp = $temp->fetch(PDO::FETCH_ASSOC)['user_type'];

                    if($temp == 0) {
                        $change_user_type = $conn->prepare("UPDATE users SET user_type = 1 WHERE id = ?");
                        $change_user_type->execute([$id]);
                    } else {
                        $change_user_type = $conn->prepare("UPDATE users SET user_type = 0 WHERE id = ?");
                        $change_user_type->execute([$id]);
                    }
                } else if (isset($_GET['action']) && $_GET['action']=='delete') {
                    $id = $_GET['id'];

                    $temp = $conn->prepare("UPDATE users SET user_type = 2 WHERE id = ?");
                    $temp->execute([$id]);
                } else if(isset($_GET['action']) && $_GET['action']=='reset') {
                    $id = $_GET['id'];
                    $default = "password";
                    $hashed = password_hash($default , PASSWORD_BCRYPT);

                    $reset_password = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $reset_password->execute([$hashed, $id]);
                }
             ?>
        <!-- Striped Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EMPLOYEES</h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-striped dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>ID NUMBER</th>
                                        <th>NAME</th>
                                        <th>USERNAME</th>
                                        <th>DEPARTMENT</th>
                                        <th>OFFICE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                    <?php
                                        $administrator_list = $conn->prepare("SELECT id, name, username, email, dept, office FROM users WHERE user_type = '0' OR user_type = '1' ORDER BY user_type");
                                        $administrator_list->execute();
                                        $x = 0;
                                            while($row = $administrator_list->fetch(PDO::FETCH_ASSOC)) {
                                                $id = $row['id'];
                                                $name = $row['name'];
                                                $username = $row['username'];
                                                $dept = $row['dept'];
                                                $office = $row['office'];
                                                $email = $row['email'];
                                            {
                                                    $find_administrator = $conn->prepare("SELECT id FROM users WHERE user_type = ? AND id = ?");
                                                    $find_administrator->execute([$_SESSION['admin_type'], $id]);

                                                    if ($find_administrator->rowCount() == 0) $admin_stat = -1;
                                                    else $admin_stat = $find_administrator->fetch(PDO::FETCH_ASSOC)['id'];

                                                    if ($find_administrator->rowCount() > 0) $admin_str = " bg-green";
                                                    else $admin_str = " btn-default";

                                                    echo '
                                                        <!-- #END# Striped Rows -->
                                                        <div class="modal fade" id="modify_employee'.$x.'" tabindex="-1" role="dialog">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="defaultModalLabel">Modify Employee</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form class="form-horizontal" method="post" action="modify_employee.php">
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                                    <label for="re">Name:</label>
                                                                                </div>
                                                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                                    <div class="form-group">
                                                                                        <div class="form-line">
                                                                                            <input value="'.$name.'" name="name" type="text" id="to" class="form-control" placeholder="Fullname" required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                                    <label for="re">Email</label>
                                                                                </div>
                                                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                                    <div class="form-group">
                                                                                        <div class="form-line">
                                                                                            <input value="'.$email.'" name="email" type="email" id="to" class="form-control" placeholder="Department" required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                                    <label for="re">Department</label>
                                                                                </div>
                                                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                                    <div class="form-group">
                                                                                        <div class="form-line">
                                                                                            <input value="'.$dept.'" name="dept" type="text" id="to" class="form-control" placeholder="Department" required>
                                                                                            <input type="hidden" name="employee_id" value="'.$id.'">
                                                                                        </div>
                                                                                        <span>Techie/NOC/IT/Marketing</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                                    <label for="re">Office</label>
                                                                                </div>
                                                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                                    <div class="form-group">
                                                                                        <div class="form-line">
                                                                                            <input value="'.$office.'" name="office" type="text" id="to" class="form-control" placeholder="Department" required>
                                                                                        </div>
                                                                                        <span>PH / FJ</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row clearfix">
                                                                                <div class="col-lg-offset-5 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                                                    <button type="submit" class="btn btn-success m-t-15 waves-effect">CONFIRM</button>
                                                                                    <button type="button" class="btn btn-warning m-t-15 waves-effect" data-dismiss="modal">CANCEL</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ';
                                                    echo '
                                                        <tr>
                                                            <td>'.$id.'</td>
                                                            <td>'.$name.'</td>
                                                            <td>'.$username.'</td>
                                                            <td>'.$dept.'</td>
                                                            <td>'.$office.'</td>
                                                            <td>
                                                                <a data-placement="top" title="Modify employee" data-target="#modify_employee'.$x.'" data-toggle="modal" type="button" class="btn btn-default waves-effect">
                                                                    <i class="material-icons">edit</i>
                                                                </a>
                                                                <a onclick="return confirm(\'Are you sure you want to grant this user an administrator priveleges?\')" data-type="with-title" data-placement="top" title="Grant administrator access" href="employees.php?action=add&id='.$id.'" type="button" class="btn '.$admin_str.' waves-effect">
                                                                    <i class="material-icons">perm_identity</i>
                                                                </a>
                                                                <a onclick="return confirm(\'Are you sure you want to soft delete this employee?\')" data-placement="top" title="Remove employee" type="button" href="employees.php?action=delete&id='.$id.'" class="btn btn-default waves-effect" data-type="with-title">
                                                                    <i class="material-icons">delete</i>
                                                                </a>
                                                                <a onclick="return confirm(\'Are you sure you want to reset user password?\')" data-type="with-title" data-placement="top" title="Reset user password" href="employees.php?action=reset&id='.$id.'" type="button" class="btn btn-default waves-effect">
                                                                    <i class="material-icons">lock_open</i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    ';
                                                    }
                                                $x++;
                                            }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

    <script src="plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/index.js"></script>
    <script src="js/pages/ui/dialogs.js"></script>
    <script src="js/custom.js"></script>
    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
