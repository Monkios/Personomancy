<?php
	$action_link = "?s=admin&a=characterLog";
?>
		<h2>Liste des modifications</h2>
<?php
	if( $show_canceled ){
?>
			<a href="<?php echo $action_link . "&show_canceled=0&show_dead=" . ( $show_dead ? 1 : 0 ); ?>">Modifications actives seulement</a>
<?php
	} else {
?>
			<a href="<?php echo $action_link . "&show_canceled=1&show_dead=" . ( $show_dead ? 1 : 0 ); ?>">Afficher toutes les modifications</a>
<?php
	}
?>
<?php
	if( $show_dead ){
?>
			<a href="<?php echo $action_link . "&show_dead=0&show_canceled=" . ( $show_canceled ? 1 : 0 ); ?>">Personnages actifs seulement</a>
<?php
	} else {
?>
			<a href="<?php echo $action_link . "&show_dead=1&show_canceled=" . ( $show_canceled ? 1 : 0 ); ?>">Afficher aussi les personnages désactivés</a>
<?php
	}
?>
		<table>
			<tr>
				<th>Personnage</th>
				<th>État Perso.</th>
				<th>État Modif.</th>
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
			$character_link = utf8_encode( $log_message->CharacterName );
			if( $user_identity->HasAccess( Identity::IS_ANIM ) ){
				$character_link = "<a href='?s=player&a=characterUpdate&c=" . $log_message->CharacterId . "'>" . $character_link . "</a>";
			}
?>
			<tr>
				<td><?php echo $character_link; ?></td>
				<td><?php echo $log_message->GetCharacterStatus(); ?></td>
				<td><?php echo $log_message->Active ? "Valide" : "Annulée"; ?></td>
				<td><?php echo utf8_encode( $log_message->PlayerName ); ?></td>
				<td><?php echo utf8_encode( $log_message->Date ); ?></td>
				<td><?php echo utf8_encode( $log_message->Text ); ?></td>
				<td><?php echo $log_message->CanBacktrack ? "Oui" : "Non"; ?></td>
			</tr>
<?php
		}
	}
?>
		</table>