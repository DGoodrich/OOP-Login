<?php
ini_set('display_errors', 1);
error_reporting(~0);
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			)
		));

		if($validation->passed()) {
			//update
			try {
				$user->update(array(
					'name' => Input::get('name')
				));

				Session::flash('home', 'Your details have been updated.');
				Redirect::to('index.php');
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}else {
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
			<h3>Update User Details</h3>
			<form action="" method="post">
				<div class="form-group <?= isset($errors['name'])? 'has-error' : '' ?>">
					<label for="name" class="control-label">Name</label>
					<input type="text" name="name" id="name" class="form-control" value="<?= escape($user->data()->name) ?>" />
					<?php if(isset($errors['name'])) { ?>
						<span class="help-block"><?= $errors['name'] ?> </span>
					<?php } ?>
				</div>

				<div class="form-group">
					<input type="submit" value="Update" class="btn btn-default" />
					<input type="button" value="Back" class="btn btn-default" onclick="window.location='index.php';" />
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
				</div>
			</form>
		</div>
	</div>
</div>