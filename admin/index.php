<!DOCTYPE html>
<html>
	<head>
		<title>Administration - Portail captif</title>
		<link rel="stylesheet" href="/css/w3.css">
	</head>
	<body>
		<div class="w3-row">
			<div class="w3-col m3"><p></p></div>
			<div class="w3-col m6 w3-container">
				<div class="w3-card-4">
					<div class="w3-panel w3-green">
						<div class="w3-center">
							<h2>Interface d'administration</h2>
						</div>
					</div>
					<form class="w3-panel" action="set_cookie.php" method="POST">
					<p>
						<input class="w3-input" type="text" name="id" value="portal_admin">
						<label class="w3-label w3-text-green">Identifiant</label>
					</p>
					<p>
						<input class="w3-input" type="password" name="pw" value="portal">
						<label class="w3-label w3-text-green">Mot de passe</label>
					</p>
					<p>
						<input class="w3-btn w3-hover-blue" type="submit" value="Valider">
					</p>
					</form>
				</div>
			</div> <!-- end of w3-container -->
			<div class="w3-col m3"><p></p></div>
		</div> <!-- end of .row -->
	</body>
</html>
