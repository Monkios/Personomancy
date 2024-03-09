		<div>
			<h2>Personnage</h2>
			<table>
				<tr>
					<th>Nom</th>
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
					<td colspan="7">Aucun personnage trouvé.</td>
				</tr>
<?php
	} else {
		foreach( $chars as $character ){
			$modify_link = "-";
			if( $character->est_vivant ){
				$modify_link = "<a href='./?s=player&a=characterUpdate&c=" . $character->id . "'>Modifier</a>";
			}
?>
				<tr>
					<td><?php echo utf8_encode( $character->nom ); ?></td>
					<td><?php echo $character->GetStatus(); ?></td>
					<td><?php echo $character->px_restants; ?> / <?php echo $character->px_totaux; ?></td>
					<td><?php echo strftime( "%e %b %Y", strtotime( $character->dernier_changement_date ) ); ?> par <?php echo utf8_encode( $character->dernier_changement_par ); ?></td>
					<td><a href="./?s=player&a=characterUpdate&c=<?php echo $character->id; ?>"><?php echo ( $character->est_vivant ) ? "Modifier" : "Voir"; ?></a></td>
					<td><a target="_blank" href="?s=player&a=sheet&c=<?php echo $character->id; ?>">Fiche</a></td>
				</tr>
<?php
		}
	}
?>
			</table>
			<ul>
<?php
	if( $can_create ){
?>
				<li><a href="?s=player&a=characterCreation">Création d'un nouveau personnage</li>
<?php
	}
?>
				<li><a href="?s=player&a=playerLog">Journal des modifications</a></li>
			</ul>
		</div>
<?php
	if( $joueur->IsAnimateur ){
?>
		<div>
			<h2>Animateurs</h2>
			<ul>
				<li><a href="?s=gm&a=characterList">Liste des personnages</a></li>
				<li><a href="?s=gm&a=printAll">Imprimer toutes les fiches</a></li>
				<li><a href="?s=gm&a=userPresences">Liste de présences</a></li>
			</ul>
		</div>
<?php
	}
	if( $joueur->IsAdministrateur ){
?>
		<div>
			<h2>Administrateurs</h2>
			<ul>
				<li><a href="?s=admin&a=assignXP">Gestion massive de l'XP</a></li>
				<li><a href="?s=admin&a=userList">Liste des joueurs</a></li>
				<li><a href="?s=admin&a=characterLog">Journal des modifications</a></li>
				<li><a href="?s=admin&a=characterTransfer">Transfert de personnage</a></li>
				<li><a href="?s=admin&a=destroyDeadCharacters">Destruction des personnages désactivés</a></li>
			</ul>
			<h3>Gestion du système</h3>
			<ul>
				<li><a href="?s=admin&a=listRaces">Gestion des races</a></li>
				<li><a href="?s=admin&a=listCapacitesRaciales">Gestion des capacités raciales</a></li>
				<ul>
					<li><a href="?s=admin&a=listChoixPouvoirs">Gestion des groupes de pouvoir</a></li>
				</ul>
				<li><a href="?s=admin&a=listCapacites">Gestion des capacités</a></li>
				<ul>
					<li><a href="?s=admin&a=listChoixCapacites">Gestion des groupes de capacité</a></li>
				</ul>
				<li><a href="?s=admin&a=listConnaissances">Gestion des connaissances</a></li>
				<ul>
					<li><a href="?s=admin&a=listChoixConnaissances">Gestion des groupes de connaissance</a></li>
				</ul>
				<li><a href="?s=admin&a=listSorts">Gestion des sorts / de l'alchimie</a></li>
			</ul>
		</div>
<?php
	}
?>