		<form method="post" action="?s=user&a=login">
			<div>
				<label for="email">Adresse courriel :</label>
				<input type="text" name="email" id="email" />
			</div>
			<div>
				<label for="password">Mot de passe :</label>
				<input type="password" name="password" id="password" />
			</div>
			<input type="submit" name="send" value="Connexion" />
		</form>
		<div class="inner_nav">
			<ul>
				<li><a href="./index.php?s=u&a=register">Nouveau compte</a></li>
				<li><a href="./index.php?s=u&a=forgot">Mot de passe oublié ?</a></li>
			</ul>
		</div>