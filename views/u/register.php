		<h1>Nouveau compte</h1>
		<p>Veuillez fournir les renseignements suivants pour faire la création de votre compte :</p>
		<form method="post" action="?s=user&a=register">
			<div>
				<label for="email">Courriel :</label>
				<input type="email" name="email" id="email" value="<?php echo $email; ?>" />
			</div>
			<div>
				<label for="firstname">Prénom :</label>
				<input type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" />
			</div>
			<div>
				<label for="lastname">Nom de famille :</label>
				<input type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>" />
			</div>
			<p><strong>Attention !</strong> Si vous avez déjà un compte et que vous en créez un nouveau pour vous éviter d'avoir à vous rappeler des informations de l'ancien, utilisez le lien "<em>Mot de passe oublié</em>" à l'accueil ou contactez l'équipe d'animation.</p>
			<div>
				<input type="submit" name="send" value="Envoyer" />
			</div>
		</form>
		<p>Un courriel contenant un mot de passe temporaire vous sera envoyé pour valider votre identité.</p>
		<div id="class">
			<a href="./index.php">Revenir à l'accueil</a>
		</div>