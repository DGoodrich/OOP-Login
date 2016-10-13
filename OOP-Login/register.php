<?php
require_once 'core/init.php';


if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$validate = new Validate();
		$validation = $validate->check($_POST, array (
			'username' => array(
				'required' => true,
				'min' => 6,
				'max' => 20,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'
			),
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			),
		));

		if($validation->passed()) {
			//register user
			$user = new User();

			$salt = Hash::salt(32);

			try {
				$user->create(array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'name' => Input::get('name'),
					'joined' => date('Y-m-d H:i:s'),
					'group' => 1,
				));

				Session::flash('home', 'You have been registered and can now log in!');
				Redirect::to('index.php');

			} catch(Exeption $e) {
				die($e->getMessage);
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
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<div class="container">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="jumbotron">
            <h3>Register New User</h3>
			<form action="" method="post">

				<div class="form-group <?= isset($errors['username'])? 'has-error' : '' ?>">
					<label for="username" class="control-label">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off" />
                    <?php if(isset($errors['username'])) { ?>
                        <span class="help-block"><?= $errors['username'] ?> </span>
                    <?php } ?>
				</div>

				<div class="form-group <?= isset($errors['password'])? 'has-error' : '' ?>">
					<label for="password" class="control-label">Password</label>
					<input type="password" name="password" id="password" class="form-control" autocomplete="off" />
                    <?php if(isset($errors['password'])) { ?>
                        <span class="help-block"><?= $errors['password'] ?> </span>
                    <?php } ?>
				</div>

				<div class="form-group <?= isset($errors['password_again'])? 'has-error' : '' ?>">
					<label for="password_again" class="control-label">Enter your password again</label>
					<input type="password" name="password_again" id="password_again" class="form-control" autocomplete="off" />
                    <?php if(isset($errors['password_again'])) { ?>
                        <span class="help-block"><?= $errors['password_again'] ?> </span>
                    <?php } ?>
				</div>

				<div class="form-group <?= isset($errors['name'])? 'has-error' : '' ?>">
					<label for="name" class="control-label">Name</label>
					<input type="text" name="name" id="name" class="form-control" value="<?php echo escape(Input::get('name')); ?>" />
                    <?php if(isset($errors['name'])) { ?>
                        <span class="help-block"><?= $errors['name'] ?> </span>
                    <?php } ?>
				</div>

				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
				<input type="submit" value="Register" class="btn btn-default" />
                <input type="button" value="Back" class="btn btn-default" onclick="window.location='index.php';" />
			</form>
		</div>
	</div>
</div>