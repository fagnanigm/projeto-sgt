<?php
	
	include("app.php");

	if(is_logged()){
		redirect('/index.php');
	}

	include(required('header'));

    include(required('menu'));
    
?>

<div class="bg-overlay">

	<div class="container padding-top-100">

		<div class="row">

			<div class="col-md-4 ml-auto mr-auto">

				<div class="account-wall">

					<div class="text-center">
						<img class="img-fluid mr-auto ml-auto" src="/assets/img/login.png" width="120" height="120">
						<p class="font-size-20">Acesso ao sistema</p>
					</div>

					<form name="frmLogin" class="form-signin margin-top-30" action="/include/model/user/get-login.php" method="post">

						<?php echo_error(); ?>

						<div class="form-group">
							<input name="email" type="text" class="form-control" placeholder="Usuario" required autofocus>
						</div>
						<div class="form-group">
							<input name="password" type="password" class="form-control" placeholder="Senha" required>
						</div>

						<div class="text-center">
							<button class="btn btn-lg btn-info" type="submit">Entrar</button>
						</div>

					</form>
				</div>

			</div>
		</div>

	</div>

</div>

<?php include(required('footer')); ?>
