<?php
    session_start();

    if(!isset($_SESSION['username'])) {
       header('location:login.php');
    }

    if(!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != 0){
        header('location:page_403.html');
    }

    require('connection.php');

    try {
        $fetch_memos = $conn->prepare("SELECT id, from_, to_, subject, content, DATE_FORMAT(time_added, '%d-%b-%y') as date_  FROM memos ORDER BY id DESC");
		$fetch_memos->execute();
        $row = $fetch_memos->fetch(PDO::FETCH_ASSOC);

        $id = $row['id'];
        $to = $row['to_'];
        $from = $row['from_'];
        $subject = $row['subject'];
        $content = $row['content'];
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
    <title>Welcome Admin | Memorandums</title>

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
   <!--  <div class="page-loader-wrapper">
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
                if(isset($_GET['action']) && $_GET['action']=='remove') {
                    $id = $_GET['id'];

                    $temp = $conn->prepare("DELETE FROM memos WHERE id = ?");
                    $temp->execute([$id]);
                 }
             ?>
        <!-- Striped Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MEMORANDUM LIST</h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-striped dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>MEMO ID</th>
                                        <th>FROM</th>
                                        <th>TO</th>
                                        <th>SUBJECT</th>
                                        <th>DATE OF RELEASE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                    <?php
                                        $administrator_list = $conn->prepare("SELECT id, from_, to_, TIME_FORMAT(time_added, '%m-%d-%Y at %h:%i %p') as time_added, subject, content FROM memos");
                                        $administrator_list->execute();
                                        $x = 0;
                                        while($row = $administrator_list->fetch(PDO::FETCH_ASSOC)) {
                                            $id = $row['id'];
                                            $from = $row['from_'];
                                            $to = $row['to_'];
                                            $subject = $row['subject'];
                                            $date = $row['time_added'];
                                        {
                                            echo '
                            <!-- #END# Striped Rows -->
                            <div class="modal fade" id="modify_employee'.$x.'" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="defaultModalLabel">Edit memorandum</h4>
                                        </div>
                                        <div class="modal-body">
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="body">
                                                                <form class="form-horizontal" method="post" action="modify_memo.php">
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                            <label for="re">To:</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                            <div class="form-group">
                                                                                <div class="form-line">
                                                                                    <input value="'.$to.'" name="to" type="text" id="to" class="form-control" placeholder="Where do you want to address your memo?" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                            <label for="from">From:</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                            <div class="form-group">
                                                                                <div class="form-line">
                                                                                    <input value="'.$from.'" name="from" type="text" id="from" class="form-control" placeholder="Who\'s writing this?" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                            <label for="re">Re:</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                            <div class="form-group">
                                                                                <div class="form-line">
                                                                                    <input value="'.$subject.'" name="re" type="text" id="re" class="form-control" placeholder="What\'s your memo all about?" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                            <label for="content">Content:</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                            <div class="form-group">
                                                                                <div class="form-line">
                                                                                     <textarea name="content" rows="4" class="form-control no-resize" placeholder="Why are you writing this?" required>"'.$content.'"</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                                    <input type="hidden" name="memo_id" value="'.$id.'">;
                                                                    </div>
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-offset-5 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                                            <button type="submit" name="submit_memo" class="btn btn-success m-t-15 waves-effect">CONFIRM</button>
                                                                            <button type="button" class="btn btn-warning m-t-15 waves-effect" data-dismiss="modal">CANCEL</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            ';
                                            echo '
                                                <tr>
                                                    <td>'.$id.'</td>
                                                    <td>'.$from.'</td>
                                                    <td>'.$to.'</td>
                                                    <td>'.$subject.'</td>
                                                    <td>'.$date.'</td>
                                                    <td>
                                                        <a data-placement="top" title="Modify memo" data-target="#modify_employee'.$x.'" data-toggle="modal" type="button" class="btn btn-default waves-effect">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                        <a onclick="return confirm(\'Are you sure?\')" data-placement="top" title="Remove memo" href="memos.php?action=remove&id='.$id.'" class="btn btn-default waves-effect" data-type="with-title">
                                                            <i class="material-icons">delete</i>
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

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
