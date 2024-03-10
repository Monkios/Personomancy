		<h2>Liste des utilisateurs</h2>
		<table>
			<tr>
				<th>Prénom</th>
				<th>Nom</th>
				<th>Courriel</th>
				<th>Nb. persos.</th>
				<th>XP Total</th>
				<th>Passe saison</th>
				<th>Activé</th>
				<th>Animateur</th>
				<th>Administrateur</th>
				<th>Insertion</th>
				<th>Mise-à-jour</th>
				<th></th>
			</tr>
<?php
	if( count( $players ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun joueur trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $players as $player ){
			$url_opts = "s=admin&a=userList&u=" . $player->Id;
?>
			<tr id="player_<?php echo $player->Id; ?>">
				<td><?php echo utf8_encode( $player->FirstName ); ?></td>
				<td><?php echo utf8_encode( $player->LastName ); ?></td>
				<td><?php echo utf8_encode( $player->Email ); ?></td>
				<td><?php echo $player->NbCharacters; ?></td>
				<td><?php echo $player->TotalExperience; ?></td>
				<td><?php echo $player->PasseSaison ? "Oui" : ""; ?></td>
				<td>
					<a href="?<?php echo $url_opts; ?>&active=<?php echo $player->IsActive ? "f" : "t"; ?>" class="<?php echo $player->IsActive ? "is_yes" : "is_no"; ?>"><?php echo $player->IsActive ? "Oui" : "-"; ?></a>
				</td>
				<td>
					<a href="?<?php echo $url_opts; ?>&anim=<?php echo $player->IsAnimateur ? "f" : "t"; ?>" class="<?php echo $player->IsAnimateur ? "is_yes" : "is_no"; ?>"><?php echo $player->IsAnimateur ? "Oui" : "-"; ?></a>
				</td>
				<td>
					<a href="?<?php echo $url_opts; ?>&admin=<?php echo $player->IsAdmin ? "f" : "t"; ?>" class="<?php echo $player->IsAdmin ? "is_yes" : "is_no"; ?>"><?php echo $player->IsAdmin ? "Oui" : "-"; ?></a>
				</td>
				<td><?php echo Date::FormatSQLDate( $player->DateInsert ); ?></td>
				<td><?php echo Date::FormatSQLDate( $player->DateModify ); ?></td>
				<td><a href="?s=admin&a=userProfile&u=<?php echo $player->Id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>