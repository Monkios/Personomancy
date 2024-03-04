		<h2>Gestion des points d'expérience</h2>
		<form method="post">
			<div>
				<p>Choisissez les personnages à affecter :</p>
				<table>
					<tr>
						<th></th>
						<th>Personnage</th>
						<th>État</th>
						<th>P. Exp.</th>
						<th>Dernière modif.</th>
					</tr>
<?php
	foreach( $player_list as $player_id => $player ){
		if( $player->IsActive && count( $character_list[ $player_id ] ) > 0 ){
?>
						<tr>
							<td colspan="6"><?php echo utf8_encode( $player->GetFullName() ); ?></td>
						</tr>
<?php
			if( count( $character_list[ $player_id ] ) == 0 ){
?>
					<tr>
						<td></td>
						<td colspan="5">Aucun personnage trouvé</td>
					</tr>
<?php
			} else {
				foreach( $character_list[ $player_id ] as $character ){
?>
					<tr>
						<td><input type="checkbox" name="character_id[]" value="<?php echo $character->id; ?>"<?php echo ( !$character->est_vivant ) ? " disabled='disabled'" : ""; ?> /></td>
						<td><?php echo utf8_encode( $character->nom ); ?></td>
						<td><?php echo $character->GetStatus(); ?></td>
						<td><?php echo $character->px_restants; ?> / <?php echo $character->px_totaux; ?></td>
						<td><?php echo strftime( "%e %b %Y", strtotime( $character->dernier_changement_date ) ); ?> par <?php echo utf8_encode( $character->dernier_changement_par ); ?></td>
					</tr>
<?php
				}
			}
		}
	}
?>
				</table>
			</div>
			<div>
				<p>Veuillez saisir le nombre de PX à ajouter (+) ou retirer (-) :</p>
				<input type="number" name="quantity_xp" />
			</div>
			<div>
				<p>Facultatif : Vous pouvez ajouter une courte description (max. 100 caractères) :</p>
				<select name="reasons_list">
					<option value="-1">-- Nouvelle / Aucune description --</option>
<?php
	foreach( $reasons_list as $reason_id => $reason_text ){
?>
					<option value="<?php echo $reason_id; ?>"><?php echo utf8_encode( $reason_text ); ?></option>
<?php
	}
?>
				</select>
				<input type="text" name="raison_xp" value="<?php echo utf8_encode( $raison_xp ); ?>" />
			</div>
			<input type="submit" name="change_xp" value="Appliquer" /><br />
		</form>