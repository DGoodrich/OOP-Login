<?php
require_once 'core/init.php';

if(!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
    ?>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-3 col-lg-offset-3">
            <div class="jumbotron">
                <h3>Profile Details</h3>

                <div class="form-group">
                    Username: <?php echo escape($data->username); ?>
                </div>

                <div class="form-group">
                    Full name: <?php echo escape($data->name); ?>
                </div>

                <div class="form-group">
                    <input type="button" value="Back" class="btn btn-default" onclick="window.location='index.php';" />
                </div>

            </div>
        </div>
    </div>

<?php } ?>

