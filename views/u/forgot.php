		<h1>Mot de passe oublié ?</h1>
		<p>Veuillez saisir l'adresse courriel associée à votre compte et nous vous enverrons un nouveau mot de passe :</p>
		<form method="post" action="?s=user&a=forgot">
			<div>
				<label for="email">Courriel :</label>
				<input type="email" name="email" id="email" value="" />
			</div>
			<div>
				<input type="submit" name="send" value="Envoyer" />
			</div>
		</form>
		<p>Un courriel vous sera envoyé pour valider votre identité.</p>
		<div id="class">
			<a href="./index.php">Revenir à l'accueil</a>
		</div>