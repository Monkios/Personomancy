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
					<td><?php echo $character->nom; ?></td>
					<td><?php echo $character->GetStatus(); ?></td>
					<td><?php echo $character->px_restants; ?> / <?php echo $character->px_totaux; ?></td>
					<td><?php echo Date::FormatSQLDate( $character->dernier_changement_date ); ?> par <?php echo $character->dernier_changement_par; ?></td>
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
				<!--<li><a href="?s=player&a=characterCreation">Création d'un nouveau personnage</a>TODO</li>-->
<?php
	}
?>
				<!--<li><a href="?s=player&a=playerLog">Journal des modifications</a>TODO</li>-->
			</ul>
		</div>
<?php
	if( $joueur->IsAnimateur ){
?>
		<div>
			<h2>Animateurs</h2>
			<!--<ul>
				<li><a href="?s=gm&a=characterList">Liste des personnages</a>TODO</li>
				<li><a href="?s=gm&a=printAll">Imprimer toutes les fiches</a>TODO</li>
				<li><a href="?s=gm&a=userPresences">Liste de présences</a>TODO</li>
			</ul>-->
		</div>
<?php
	}
	if( $joueur->IsAdministrateur ){
?>
		<div>
			<h2>Administrateurs</h2>
			<ul>
				<li><a href="?s=admin&a=userList">Gestion des utilisateurs</a></li>
				<!--<li><a href="?s=admin&a=assignXP">Gestion massive de l'XP</a>TODO</li>
				<li><a href="?s=admin&a=characterLog">Journal des modifications</a>TODO</li>
				<li><a href="?s=admin&a=characterTransfer">Transfert de personnage</a>TODO</li>
				<li><a href="?s=admin&a=destroyDeadCharacters">Destruction des personnages désactivés</a>TODO</li>-->
			</ul>
			<h3>Configurations du système</h3>
			<ul>
				<li><a href="?s=admin&a=listCapacites">Gestion des capacités</a></li>
				<li><a href="?s=admin&a=listCitesEtats">Gestion des cités-états</a></li>
				<li><a href="?s=admin&a=listConnaissances">Gestion des connaissances</a></li>
				<li><a href="?s=admin&a=listCroyances">Gestion des croyances</a></li>
				<li><a href="?s=admin&a=listVoies">Gestion des voies</a></li>
				<!--<li><a href="?s=admin&a=listRaces">Gestion des races</a>TODO</li>
				<li><a href="?s=admin&a=listCapacitesRaciales">Gestion des capacités raciales</a>TODO</li>
				<ul>
					<li><a href="?s=admin&a=listChoixCapacites">Gestion des groupes de capacité</a>TODO</li>
				</ul>
				<li><a href="?s=admin&a=listConnaissances">Gestion des connaissances</a>TODO</li>-->
			</ul>
		</div>
<?php
	}
?>