		<h1>Mon profil</h1>
		<form method="post" action="?s=user&a=profile">
			<div>
				<h2>Mettre à jour</h2>
				<p>Voici les renseignements fournis lors de la dernière mise-à-jour de votre compte :</p>
				<div>
					<label for="lastname">Date d'inscription :</label>
					<span><?php echo Date::FormatSQLDate( $joueur->DateInsert ); ?></span>
				</div>
				<div>
					<label for="lastname">Dernière modification :</label>
					<span><?php echo Date::FormatSQLDate( $joueur->DateModify ); ?></span>
				</div>
			
				<div>
					<label for="email">Courriel :</label>
					<input type="email" name="email" id="email" value="<?php echo $joueur->Email; ?>" />
				</div>
				<div>
					<label for="firstname">Prénom :</label>
					<input type="text" name="firstname" id="firstname" value="<?php echo $joueur->FirstName; ?>" />
				</div>
				<div>
					<label for="lastname">Nom de famille :</label>
					<input type="text" name="lastname" id="lastname" value="<?php echo $joueur->LastName; ?>" />
				</div>
				<h2>Changer mon mot de passe</h2>
				<div>
					<label for="password">Nouveau mot de passe :</label>
					<input type="password" name="password" id="password" value="" />
				</div>
				<p>NB: Si vous changez votre mot de passe, vous devrez réactiver votre compte à l'aide du courriel de confirmation qui vous sera envoyé.</p>
				<div>
					<input type="submit" name="send" value="Envoyer" />
				</div>
			</div>
		</form>
		<div id="class">
			<a href="./index.php">Revenir à l'accueil</a>
		</div>