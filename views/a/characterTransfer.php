		<h2>Transfert de personnage</h2>
		<form method="post">
			<div>
				<p>Choisissez les personnages à transférer :</p>
				<table>
					<tr>
						<th></th>
						<th>Id</th>
						<th>Personnage</th>
						<th>État</th>
						<th>Joueur</th>
					</tr>
<?php
	if( count( $chars ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun personnage trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $chars as $char_id => $character ){
?>
					<tr>
						<td><input type="checkbox" name="character_id[]" value="<?php echo $char_id; ?>" /></td>
						<td><?php echo $char_id; ?></td>
						<td><?php echo $character->nom; ?></td>
						<td><?php echo $character->GetStatus(); ?></td>
						<td><?php echo $character->joueur_nom; ?></td>
					</tr>
<?php
		}
	}
?>
				</table>
			</div>
			<div>
				<p>Choisissez le joueur à qui les transférer :</p>
				<select name="player_id">
<?php
	foreach( $players as $player_id => $player ){
?>
					<option value="<?php echo $player_id; ?>"><?php echo $player->getFullName(); ?></option>
<?php
	}
?>
				</select>
			</div>
			<input type="submit" name="transfer_character" value="Transférer" /><br />
		</form>