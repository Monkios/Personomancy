		<h2>Destruction de personnages morts</h2>
		<form method="post">
			<div>
				<p>Choisissez les morts à détruire :</p>
				<table>
					<tr>
						<th></th>
						<th>Personnage</th>
						<th>Joueur</th>
						<th>Tué le</th>
					</tr>
<?php
	if( count( $undeads ) == 0 ){
?>
			<tr>
				<td colspan="4">Aucun personnage trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $undeads as $char_id => $character ){
?>
					<tr>
						<td><input type="checkbox" name="character_id[]" value="<?php echo $char_id; ?>" /></td>
						<td><?php echo $character->nom; ?></td>
						<td><?php echo $character->joueur_nom; ?></td>
						<td><?php echo Date::FormatSQLDate( $character->dernier_changement_date ); ?></td>
					</tr>
<?php
		}
	}
?>
				</table>
			</div>
			<input type="submit" name="destroy_character" value="Détruire" /><br />
		</form>