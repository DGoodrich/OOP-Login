<?php
require_once 'core/init.php';

if(Session::exists('home')) {
    echo '<div class="alert alert-success"><p>' .  Session::flash('home') . '</p></div>';
}
?>
<link rel="stylesheet" href="css/bootstrap.css">

<div class="container">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jumbotron">

            <?php

            $user = new User();
            if($user->isLoggedIn()) {
                ?>
                <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>

                <ul>
                    <li><a href="logout.php">Log out</a></li>
                    <li><a href="update.php">Update details</a></li>
                    <li><a href="changepassword.php">Change password</a></li>
                </ul>
                <?php
                //Could use 'moderator' as well or whatever you have set in your permissions group
                if($user->hasPermission('admin')) {
                    echo '<p>You are an administrator!</p>';
                }

            } else { ?>
                <p>You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>
            <?php } ?>
        </div>
    </div>
</div>