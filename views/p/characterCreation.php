		<h2>Création de votre personnage</h2>
		<p>Veuillez sélectionner les bases de votre personnage.
		<form method="post">
			<div>
				<label for="character_name">Nom complet :</label>
				<input type="text" name="character_name" id="character_name" value="<?php echo $character_name; ?>" />
			</div>
			<div>
				<label for="character_alignment">Alignement :</label>
				<select name="character_alignment" id="character_alignment">
					<option>== Veuillez choisir un alignement ==</option>
<?php
	foreach( $list_alignements as $id => $nom ){
?>
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_alignment ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
				</select>
			</div>
			<div>
				<label for="character_faction">Faction :</label>
				<select name="character_faction" id="character_faction">
					<option>== Veuillez choisir une faction ==</option>
<?php
	foreach( $list_factions as $id => $nom ){
		if($id != 1 ){
?>
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_faction ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
				</select>
			</div>
			<div>
				<label for="character_religion">Religion :</label>
				<select type="text" name="character_religion" id="character_religion">
					<option>== Veuillez choisir une religion ==</option>
<?php
	$pantheon = "";
	foreach( $list_religions as $id => $religion ){
		if( $religion[ "pantheon" ] != $pantheon ){
			if( $pantheon != "" ){
?>
						</optgroup>
<?php
			}
?>
						<optgroup label="<?php echo utf8_encode( $religion[ "pantheon" ] ); ?>">
<?php
			$pantheon = $religion[ "pantheon" ];
		}
?>
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_religion ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $religion[ "nom" ] ); ?></option>
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
					<option value="<?php echo $id; ?>"<?php echo ( $id === $character_race ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
				</select>
			</div>
			<p>Ces informations ne sont pas finales. Vous pourrez les modifier une fois le personnage créé.</p>
			<input type="submit" name="character_create" value="Enregistrer" />
		</form>