<?php
require_once 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if ($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if ($login) {
                Redirect::to('index.php');
            } else {
                echo '<div class="alert alert-danger"><p>Sorry, login failed</p></div>';
            }

        } else {
            $errors = [];
            foreach($validation->errors() as $error) {
                $errors[strtok($error, " ")] = $error;
            }
        }
    }
}
?>

<link rel="stylesheet" href="css/bootstrap.css">

<div class="container">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-3 col-lg-offset-3">
        <div class="jumbotron">
            <h2>Login Page</h2>
            <form action="" method="post" autocomplete="off">
                <div class="form-group <?= isset($errors['username'])? 'has-error' : '' ?>">
                    <label for="username" class="control-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control"/>
                    <?php if(isset($errors['username'])) { ?>
                        <span class="help-block"><?= $errors['username'] ?> </span>
                    <?php } ?>
                </div>

                <div class="form-group <?= isset($errors['password'])? 'has-error' : '' ?>">
                    <label for="password" class="control-label">password</label>
                    <input type="password" name="password" id="password" class="form-control"/>
                    <?php if(isset($errors['password'])) { ?>
                        <span class="help-block"><?= $errors['password'] ?> </span>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label for="remember">
                        <input type="checkbox" name="remember" id="remember"/> Remeber me
                    </label>
                </div>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                <input type="submit" value="Log in" class="btn btn-default"/>
                <input type="button" value="Back" class="btn btn-default" onclick="window.location='index.php';" />
            </form>
        </div>
    </div>
</div>
