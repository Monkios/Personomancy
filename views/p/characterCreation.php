		<h2>Création de votre personnage</h2>
		<p>Veuillez sélectionner les bases de votre personnage.
		<form method="post">
			<div>
				<label for="character_name">Nom complet :</label>
				<input type="text" name="character_name" id="character_name" value="<?php echo $character_name; ?>" />
			</div>
			
			<div>
				<label for="character_cite_etat">Cité-État :</label>
				<select name="character_cite_etat" id="character_cite_etat">
					<option>== Veuillez choisir une cité-État ==</option>
<?php
	foreach( $list_cites_etats as $id => $nom ){
?>
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_cite_etat ) ? " selected='selected'" : ""; ?>><?php echo $nom; ?></option>
<?php
	}
?>
				</select>
			</div>
			<div>
				<label for="character_croyance">Croyance :</label>
				<select type="text" name="character_croyance" id="character_croyance">
					<option>== Veuillez choisir une croyance ==</option>
<?php
	foreach( $list_croyances as $id => $croyance ){
?>
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_croyance ) ? " selected='selected'" : ""; ?>><?php echo $croyance[ "nom" ]; ?></option>
<?php
	}
?>
				</select>
			</div>
			<div>
				<label for="character_race">Race :</label>
				<select type="text" name="character_race" id="character_race">
					<option>== Veuillez choisir une race ==</option>
<?php
	foreach( $list_races as $id => $nom ){
?>
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_race ) ? " selected='selected'" : ""; ?>><?php echo $nom; ?></option>
<?php
	}
?>
				</select>
			</div>
			<p>Ces informations ne sont pas finales. Vous pourrez les modifier une fois le personnage créé.</p>
			<input type="submit" name="character_create" value="Enregistrer" />
		</form>