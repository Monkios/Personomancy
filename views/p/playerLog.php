		<h2>Modifications sur vos personnages</h2>
		<table>
			<tr>
				<th>Personnage</th>
				<th>État</th>
				<th>Faite par</th>
				<th>Date</th>
				<th>Description</th>
				<th>Remboursable</th>
			</tr>
<?php
	if( count( $list ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun personnage trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $list as $log_message ){
?>
			<tr>
				<td><a href="?s=player&a=characterUpdate&c=<?php echo $log_message->CharacterId; ?>"><?php echo $log_message->CharacterName; ?></a></td>
				<td><?php echo $log_message->GetCharacterStatus(); ?></td>
				<td><?php echo $log_message->PlayerName; ?></td>
				<td><?php echo $log_message->Date; ?></td>
				<td><?php echo $log_message->Text; ?></td>
				<td><?php echo $log_message->CanBacktrack ? "Oui" : "Non"; ?></td>
			</tr>
<?php
		}
	}
?>
		</table>