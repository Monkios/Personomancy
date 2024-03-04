<?php
	$action_link = "?s=gm&a=characterList";
?>
		<h2>Liste des personnages <strong>[<?php echo ( $only_alives ? "VIVANTS" : "TOUS" ); ?>]</strong></h2>
		<div>
<?php
	if( $only_alives ){
?>
			<a href="<?php echo $action_link . "&sort=" . $sort_by . "&alive=n"; ?>">Afficher tous les personnages</a>
<?php
	} else {
?>
			<a href="<?php echo $action_link . "&sort=" . $sort_by . "&alive=y"; ?>">Afficher les personnages vivants seulement</a>
<?php
	}
?>
		</div>
		<table>
			<tr>
				<th>Id</th>
				<th><a href="<?php echo $action_link . "&alive=" . ( $only_alives ? "y" : "n" ); ?>&sort=character">Personnage</a></th>
				<th><a href="<?php echo $action_link; ?>&sort=player">Joueur</a></th>
				<th>État</th>
				<th>P. Exp.</th>
				<th>Dernière modif.</th>
				<th></th>
				<th></th>
			</tr>
<?php
	if( count( $chars ) == 0 ){
?>
			<tr>
				<td colspan="9">Aucun personnage trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $chars as $character ){
?>
			<tr>
				<td><?php echo $character->id; ?></td>
				<td><?php echo utf8_encode( $character->nom ); ?></td>
				<td><?php echo utf8_encode( $character->joueur_nom ); ?></td>
				<td><?php echo $character->GetStatus(); ?></td>
				<td><?php echo $character->px_restants; ?> / <?php echo $character->px_totaux; ?></td>
				<td><?php echo strftime( "%e %b %Y", strtotime( $character->dernier_changement_date ) ); ?> par <?php echo utf8_encode( $character->dernier_changement_par ); ?></td>
				<td><a href="?s=player&a=characterUpdate&c=<?php echo $character->id; ?>">Modifier</a></td>
				<td><a href="?s=player&a=sheet&c=<?php echo $character->id; ?>" target="_blank">Fiche</a></td>
			</tr>
<?php
		}
	}
?>
		</table>