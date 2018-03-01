<?php
echo '
    <li class="active">
        <a href="index.php">
            <i class="material-icons">home</i>
            <span>Home</span>
        </a>
        <a href="../timeclock-app/index.php">
            <i class="material-icons">perm_identity</i>
            <span>Client</span>
        </a>';


    if($_SESSION['admin_type'] == 0){
        echo '
            <a href="new_memo.php">
                <i class="material-icons">announcement</i>
                <span>Office memo</span>
            </a>

                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">work</i>
                    <span>Employees</span>
                </a>
                    <ul class="ml-menu">
                        <li><a href="new_employee.php">New employee</a></li>
                        <li><a href="note_employees.php">Employees\' Notes</a></li>
                        <li><a href="break_employees.php">Employees\' Breaks</a></li>
                        <li><a href="inactive_employees.php">Inactive employees</a></li>
                    </ul>
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">view_list</i>
                <span>Tables</span>
            </a>
                <ul class="ml-menu">
                    <li><a href="administrators.php">Administrators</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="memos.php">Memorandums</a></li>
                </ul>
             <a href="newpassword.php">
            <i class="material-icons">settings</i>
            <span>Change Password</span>
        </a>
        </li>
        ';
         // 
        }

?>
