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
	if( $joueur->IsAdmin ){
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
		
		<div>
			<h2>TODOs</h2>
			<ul>
				<li class="done">V 0.0</li>
				<ul>
					<li class="done">Objets d'infra de base</li>
					<li class="done">Gestion la sécurité</li>
					<li class="done">Authentification d'un utilisateur</li>
				</ul>
				<li class="done">V 0.1</li>
				<ul>
					<li class="done">Création/Gestion de compte</li>
					<li class="done">Administration de compte</li>
					<li class="done">BUG - UTF8 decode à l'écriture</li>
					<li class="done">BUG - UTF8 encode à la lecture</li>
				</ul>
				<li class="done">V 0.2</li>
				<ul>
					<li class="done">Diviser Utils en Domains et Services</li>
					<li class="done">CastDomain retourne des persos partiels</li>
					<li class="done">Diviser CommunityDomain et IdentityDomain</li>
					<li class="done">Standardiser la capitalisation des paramètres</li>
				</ul>
				<li>V 1.0 Alpha</li>
				<ul>
					<li class="done">Nouveau format de fiche de persos</li>
					<li class="done">Transfert massif de personnage</li>
					<li class="done">Destruction massif des morts</li>
					<li class="done">Gestion massive de l'XP</li>
					<li class="done">Journal des modifications</li>
				</ul>
				<li>V 1.0 Beta</li>
				<ul>
					<li class="done">Création d'un nouveau personnage</li>
					<li class="done">Modification d'un personnage</li>
					<li class="done">Ajouts automatiques à la création</li>
					<li class="done">Affichage complet du journal d'un personnage</li>
				</ul>
				<li>V 1.0 RC</li>
				<ul>
					<li>Affichage des gains d'XP d'un personnage</li>
					<li>Feuille de style de base</li>
					<li>Correction des bogues découverts</li>
				</ul>
				<li>V 1.1</li>
				<ul>
					<li>Impression du spellbook d'un personnage</li>
					<li>Nettoyage du code</li>
					<li>...</li>
				</ul>
				<li>V 1.2</li>
				<ul>
					<li>Gestion des alignements</li>
					<li>Gestion des dieux</li>
					<li>Gestion des capacités</li>
					<li>Gestion des connaissances</li>
					<li>Gestion des sorts/potions</li>
					<li>Gestion des pouvoirs</li>
					<li>Gestion des races</li>
					<li>Déresponsabilisation de la couche Dépôt du Personnage</li>
				</ul>
				<li>V 2.0</li>
				<ul>
					<li>Transférer la possession d'XP au joueur (faisabilité ?)</li>
					<li>Identification des NPCs</li>
				</ul
			</ul>
			<style type="text/css">
				.done { text-decoration: line-through;
			</style>
		</div>
<?php
	}
?>