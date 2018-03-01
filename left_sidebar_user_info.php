<?php
echo '
    <div class="image">
        <img src="images/user.png" width="48" height="48" alt="User" />
    </div>
    <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$_SESSION['full_name'].'</div>
        <div class="email">'.$_SESSION['user_email'].'</div>
    </div>

';
?>
