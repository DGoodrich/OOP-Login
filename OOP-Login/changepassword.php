<?php
ini_set('display_errors', 1);
error_reporting(~0);
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedin()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
        $errors = [];
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'current_password' => array(
				'required' => true,
				'min' => 6
			),
			'new_password' => array(
				'required' => true,
				'min' => 6
			),
			'new_password_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'new_password'
			)
		));

		if($validation->passed()) {

			if(Hash::make(Input::get('current_password'), $user->data()->salt) !== $user->data()->password) {
                $errors['current_password'] = 'Your current password is wrong';
			} else {
				$salt = Hash::salt(32);
				$user->update(array(
					'password' => Hash::make(Input::get('new_password'), $salt),
					'salt' => $salt
				));

				Session::flash('home', 'Your password has been changed!');
				Redirect::to('index.php');
			}

		} else {

            foreach($validation->errors() as $error) {
                $errors[strtok($error, " ")] = $error;
            }
		}
	}
}
?>
<link rel="stylesheet" href="css/bootstrap.css">

<div class="container">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="jumbotron">
			<h2>Change Password</h2>
			<form action="" method="post">
				<div class="form-group <?= isset($errors['current_password'])? 'has-error' : '' ?>">
					<label for="current_password" class="control-label">Current password</label>
					<input type="password" class="form-control" name="current_password" id="current_password" />
                    <?php if(isset($errors['current_password'])) { ?>
                        <span class="help-block"><?= $errors['current_password'] ?> </span>
                    <?php } ?>
				</div>

				<div class="form-group <?= isset($errors['new_password'])? 'has-error' : '' ?>">
					<label for="new_password" class="control-label">New password:</label>
					<input type="password" class="form-control" name="new_password" id="new_password" />
                    <?php if(isset($errors['new_password'])) { ?>
                        <span class="help-block"><?= $errors['new_password'] ?> </span>
                    <?php } ?>
				</div>

				<div class="form-group <?= isset($errors['new_password_again'])? 'has-error' : '' ?>">
					<label for="new_password_again" class="control-label">New password again:</label>
					<input type="password" class="form-control" name="new_password_again" id="new_password_again" />
                    <?php if(isset($errors['new_password_again'])) { ?>
                        <span class="help-block"><?= $errors['new_password_again'] ?> </span>
                    <?php } ?>
				</div>

				<input type="submit" value="Change" class="btn btn-default" />
				<input type="button" value="Back" class="btn btn-default" onclick="window.location='index.php';" />
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
			</form>
		</div>
	</div>
</div>