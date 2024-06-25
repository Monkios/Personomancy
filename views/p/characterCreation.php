		<h2>Création de votre personnage</h2>
		<p>Veuillez sélectionner les bases de votre personnage.
		<form method="post">
			<div>
				<h3>Nom complet :</h3>
				<input type="text" name="character_name" id="character_name" value="<?php echo $character_name; ?>" />
			</div>
			
			
			<div>
				<h3>Race :</h3>
				<dl>
<?php
	foreach( $list_races as $id => $race ){
?>
					<dt>
						<?php echo $race->nom; ?>
						<input type="radio" name="character_race" value="<?php echo $race->id; ?>"<?php echo ( $race->id === $character_race ) ? " checked='checked'" : ""; ?> />
					</dt>
					<dd><?php echo $race->description; ?></dd>
<?php
	}
?>
				</dl>
			</div>
			<div>
				<h3>Demi-race (race secondaire) :</h3>
				<div>
					<input type="checkbox" onclick="afficher_demi_races( this.checked );"<?php echo ( $character_race_secondaire != 0 ) ? " checked='checked'" : ""; ?> /> Afficher les demi-races
				</div>
				<div class="hidden" id="section_races_secondaires">
					<p>Il est possible que deux humanoïdes de races différentes décident de procréer. Cela crée des races singulières comme les demi-elfes ou les demi-nains. Comme c’est un fait rare, on n’en rencontre peu sur les terres. Il est toutefois possible pour un joueur d’incarner des demi-races. Pour ce faire, le joueur choisit l’une des deux races de son personnage; cette race déterminera le bassin de capacités raciales dans lequel il pourra puiser et les prérequis de costume de son personnage. 
					<ul>
						<li>
							Ne s'applique pas
							<input type="radio" name="character_race_secondaire" value="0"<?php echo ( 0 === $character_race_secondaire ) ? " checked='checked'" : ""; ?> />
						</li>
<?php
	foreach( $list_races as $id => $race ){
?>
						<li>
							<?php echo $race->nom; ?>
							<input type="radio" name="character_race_secondaire" value="<?php echo $race->id; ?>"<?php echo ( $race->id === $character_race_secondaire ) ? " checked='checked'" : ""; ?> />
						</li>
<?php
	}
?>
					</ul>
				</div>
			</div>
			<div>
				<h3>Cité-État :</h3>
				<dl>
<?php
	foreach( $list_cites_etats as $id => $cite_etat ){
?>
					<dt>
						<?php echo $cite_etat->nom; ?>
						<input type="radio" name="character_cite_etat" value="<?php echo $cite_etat->id; ?>"<?php echo ( $cite_etat->id === $character_cite_etat ) ? " checked='checked'" : ""; ?> />
					</dt>
					<dd><?php echo $cite_etat->description; ?></dd>
<?php
	}
?>
				</dl>
			</div>
			<div>
				<h3>Croyance :</h3>
				<dl>
<?php
	foreach( $list_croyances as $id => $croyance ){
?>
					<dt>
						<?php echo $croyance->nom; ?>
						<input type="radio" name="character_croyance" value="<?php echo $croyance->id; ?>"<?php echo ( $croyance->id === $character_croyance ) ? " checked='checked'" : ""; ?> />
					</dt>
					<dd><?php echo $croyance->description; ?></dd>
<?php
	}
?>
				</dl>
			</div>
			<p>Ces informations ne sont pas finales. Vous pourrez les modifier une fois le personnage créé.</p>
			<input type="submit" name="character_create" value="Enregistrer" />
		</form>
		<script type="text/javascript">
			function afficher_demi_races( afficher ){
				if( afficher ){
					document.getElementById( "section_races_secondaires" ).style.display = "block";
				} else {
					document.getElementById( "section_races_secondaires" ).style.display = "none";
				}
			}
<?php
	if( $character_race_secondaire == 0 ){
?>
			afficher_demi_races( false );
<?php
	} else {
?>
			afficher_demi_races( true );
<?php
	}
?>
		</script>