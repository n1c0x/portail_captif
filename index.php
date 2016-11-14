<!DOCTYPE html>
<html>
	<head>
		<title>Portail captif</title>
		<link rel="stylesheet" href="/css/w3.css">
	</head>
	<body>
		<div class="w3-row">
			<div class="w3-col m3"><p></p></div>
			<div class="w3-col m6 w3-container">
				<div class="w3-card-4">
					<div class="w3-panel w3-blue">
						<div class="w3-center">
							<h2>Acc&egrave;s au r&eacute;seau</h2>
						</div>
					</div>
					<form class="w3-panel" action="login.php" method="POST">
					<p>
						<input class="w3-input" type="text" name="id">
						<label class="w3-label w3-text-blue">Identifiant</label>
					</p>
					<p>
						<input class="w3-input" type="password" name="pw">
						<label class="w3-label w3-text-blue">Mot de passe</label>
					</p>
					<p>
						<input class="w3-btn w3-hover-blue" type="submit" value="Valider">
					</p>
					</form>
				</div>
			</div> <!-- end of w3-container w3-border w3-center -->
			<div class="w3-col m3"><p></p></div>
		</div> <!-- end of .row -->
	</body>
</html>
