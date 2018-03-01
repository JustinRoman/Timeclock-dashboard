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

        $input = $_POST['e_id'];
        $name = "'$input'";
        
        //echo $name;

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
        <div class="container-fluid">
            <div id="myScheduler"> </div>
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

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>

    <!-- CUSTOM -->
    <script type="text/javascript" src="https://cdn.alloyui.com/3.0.1/aui/aui-min.js"></script>
    <script type="text/javascript">
	    YUI().use(
		  'aui-scheduler',
		  function(Y) {
		    var events = [
  			<?php
            $result = $db->query("SELECT name, DATE_FORMAT(time_in, '%Y') as y, DATE_FORMAT(time_in, '%m') as m, DATE_FORMAT(time_in, '%d') as d, TIME_FORMAT(time_in, '%H') as h, TIME_FORMAT(time_in, '%i') as mi, TIME_FORMAT(time_in, '%s') as s, DATE_FORMAT(time_out, '%Y') as oy, DATE_FORMAT(time_out, '%m') as omi, DATE_FORMAT(time_out, '%d') as od, TIME_FORMAT(time_out, '%H') as oh, TIME_FORMAT(time_out, '%i') as omi, TIME_FORMAT(time_out, '%s') as os FROM timestamps WHERE time_out < CURDATE() AND name = " . $name);
  	      			if($result->num_rows > 0) {
  						while($row = $result->fetch_assoc()) {
  							echo "{
  								color: 'blue',
  								content: 'Login time',
  								startDate: new Date(".$row['y'].", ".(char_at($row['m'], 0) == '0' ? char_at($row['m'], 1) - 1 : $row['m'] - 1).", ".(char_at($row['d'], 0) == '0' ? char_at($row['d'], 1) : $row['d']).", ".(char_at($row['h'], 0) == '0' ? char_at($row['h'], 1) : $row['h']).", ".(char_at($row['mi'], 0) == '0' ? char_at($row['mi'], 1) : $row['mi'])."),
  								endDate: new Date(".$row['oy'].", ".(char_at($row['m'], 0) == '0' ? char_at($row['m'], 1) - 1 : $row['m'] - 1).", ".(char_at($row['od'], 0) == '0' ? char_at($row['od'], 1) : $row['od']).", ".(char_at($row['oh'], 0) == '0' ? char_at($row['oh'], 1) : $row['oh']).", ".(char_at($row['mi'], 0) == '0' ? char_at($row['omi'], 1) : $row['omi']).")
  							},\n";
  						}
  					}

                        $result = $db->query("SELECT u.name,
                        DATE_FORMAT(b.break_start, '%Y') as y, 
                        DATE_FORMAT(b.break_start, '%m') as m, 
                        DATE_FORMAT(b.break_start, '%d') as d, 
                        TIME_FORMAT(b.break_start, '%H') as h, 
                        TIME_FORMAT(b.break_start, '%i') as mi, 
                        TIME_FORMAT(b.break_start, '%s') as s, 
                        DATE_FORMAT(b.break_end, '%Y') as oy,  
                        DATE_FORMAT(b.break_end, '%m') as omi,  
                        DATE_FORMAT(b.break_end, '%d') as od, 
                        TIME_FORMAT(b.break_end, '%H') as oh,  
                        TIME_FORMAT(b.break_end, '%i') as omi,  
                        TIME_FORMAT(b.break_end, '%s') as os 
                        FROM breaks b  
                        JOIN users u
                        ON b.employee_id = u.id 
                        WHERE u.name = ". $name);
  	      			if($result->num_rows > 0) {
  						while($row = $result->fetch_assoc()) {
  							echo "{
  								color: 'red',
  								content: 'Break note',
  								startDate: new Date(".$row['y'].", ".(char_at($row['m'], 0) == '0' ? char_at($row['m'], 1) - 1 : $row['m'] - 1).", ".(char_at($row['d'], 0) == '0' ? char_at($row['d'], 1) : $row['d']).", ".(char_at($row['h'], 0) == '0' ? char_at($row['h'], 1) : $row['h']).", ".(char_at($row['mi'], 0) == '0' ? char_at($row['mi'], 1) : $row['mi'])."),
  								endDate: new Date(".$row['oy'].", ".(char_at($row['m'], 0) == '0' ? char_at($row['m'], 1) - 1 : $row['m'] - 1).", ".(char_at($row['od'], 0) == '0' ? char_at($row['od'], 1) : $row['od']).", ".(char_at($row['oh'], 0) == '0' ? char_at($row['oh'], 1) : $row['oh']).", ".(char_at($row['mi'], 0) == '0' ? char_at($row['omi'], 1) : $row['omi']).")
  							},\n";
  						}
  					}
  				?>
		    ];

		    var dayView = new Y.SchedulerDayView();
		    var weekView = new Y.SchedulerWeekView();
		    var monthView = new Y.SchedulerMonthView();

		    new Y.Scheduler({
		        activeView: monthView,
		        boundingBox: '#myScheduler',
		        items: events,
		        render: true,
		        views: [dayView, weekView, monthView]
	      	});
		  }
		);
    </script>
</body>

</html>
