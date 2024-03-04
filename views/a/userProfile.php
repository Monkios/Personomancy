		<h2>Modifier un profil</h2>
		<form method="post" action="?s=admin&a=userProfile&u=<?php echo $player->Id; ?>">
			<div>
				<h3>Renseignements</h3>
				<div>
					<label for="lastname">Date d'inscription :</label>
					<span><?php echo strftime( "%e %b %Y", strtotime( $player->DateInsert ) ); ?></span>
				</div>
				<div>
					<label for="lastname">Dernière modification :</label>
					<span><?php echo strftime( "%e %b %Y %H:%M", strtotime( $player->DateModify ) ); ?></span>
				</div>
				<div>
					<label>Est actif :</label>
					<span><?php echo ( $player->IsActive ) ? "Oui" : "Non"; ?></span>
				</div>
				<div>
					<label>Est animateur :</label>
					<span><?php echo ( $player->IsAnimateur ) ? "Oui" : "Non"; ?></span>
				</div>
				<div>
					<label>Est administrateur :</label>
					<span><?php echo ( $player->IsAdmin ) ? "Oui" : "Non"; ?></span>
				</div>
				<div>
					<label>Passe saison :</label>
					<span><?php echo ( $player->PasseSaison ) ? "Oui" : "Non"; ?></span>
<?php
	if( !$player->PasseSaison ){
?>
					<input type="submit" name="add_passe_saison" id="add_passe_saison" value="Ajouter la passe saison" />
<?php
	} else {
?>
					<input type="submit" name="remove_passe_saison" id="remove_passe_saison" value="Retirer la passe saison" />
<?php
	}
?>
				</div>
				<div>
					<label for="email">Courriel :</label>
					<input type="email" name="email" id="email" value="<?php echo utf8_encode( $player->Email ); ?>" />
				</div>
				<div>
					<label for="firstname">Prénom :</label>
					<input type="text" name="firstname" id="firstname" value="<?php echo utf8_encode( $player->FirstName ); ?>" />
				</div>
				<div>
					<label for="lastname">Nom de famille :</label>
					<input type="text" name="lastname" id="lastname" value="<?php echo utf8_encode( $player->LastName ); ?>" />
				</div>
				<div>
					<input type="submit" name="send" value="Envoyer" />
				</div>
			</div>
			<div>
				<h3>Activation du compte</h3>
				<div>
					<label for="force_activation">Forcer l'activation</label>
					<input type="submit" name="force_activation" id="force_activation" value="Envoyer" />
				</div>
				<div>
					<label for="change_password">Générer un mot de passe</label>
					<input type="submit" name="change_password" id="change_password" value="Envoyer" />
				</div>
			</div>
		</form>
		<div>
			<a href="?s=admin&a=userList#player_<?php echo $player->Id; ?>">Revenir à la liste des joueurs</a>
		</div>