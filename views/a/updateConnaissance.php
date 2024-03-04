		<h2>Modification d'une connaissance</h2>
		<div>
			<form method="post" action="?s=admin&a=updateConnaissance&i=<?php echo $connaissance->id; ?>">
				<div>
					<label for="connaissance_nom">Nom :</label>
					<input type="text" name="connaissance_nom" id="connaissance_nom" value="<?php echo utf8_encode( $connaissance->nom ); ?>" />
				</div>
				<div>
					<label for="connaissance_active">Est activée</label>
					<input type="checkbox" name="connaissance_active" id="connaissance_active"<?php if( $connaissance->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Prérequis</h3>
					<div>
						<label for="connaissance_statistique_prim_id">Statistique primaire</label>
						<select id="connaissance_statistique_prim_id" name="connaissance_statistique_prim_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_statistiques as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_statistique_prim_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
						<select id="connaissance_statistique_prim_sel" name="connaissance_statistique_prim_sel">
<?php
	for( $i = 0; $i <= 15; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $i == $connaissance->prereq_statistique_prim_sel ){ echo " selected='selected'"; } ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_statistique_sec_id">Statistique secondaire</label>
						<select id="connaissance_statistique_sec_id" name="connaissance_statistique_sec_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_statistiques as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_statistique_sec_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
						<select id="connaissance_statistique_sec_sel" name="connaissance_statistique_sec_sel">
<?php
	for( $i = 0; $i <= 15; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $i == $connaissance->prereq_statistique_sec_sel ){ echo " selected='selected'"; } ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_capacite_prim_id">Capacité primaire</label>
						<select id="connaissance_capacite_prim_id" name="connaissance_capacite_prim_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_capacites as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_capacite_prim_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
						<select id="connaissance_capacite_prim_sel" name="connaissance_capacite_prim_sel">
<?php
	for( $i = 0; $i <= 15; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $i == $connaissance->prereq_capacite_prim_sel ){ echo " selected='selected'"; } ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_capacite_sec_id">Capacité secondaire</label>
						<select id="connaissance_capacite_sec_id" name="connaissance_capacite_sec_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_capacites as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_capacite_sec_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
						<select id="connaissance_capacite_sec_sel" name="connaissance_capacite_sec_sel">
<?php
	for( $i = 0; $i <= 15; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $i == $connaissance->prereq_capacite_sec_sel ){ echo " selected='selected'"; } ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_connaissance_prim_id">Connaissance primaire</label>
						<select id="connaissance_connaissance_prim_id" name="connaissance_connaissance_prim_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_connaissances as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_connaissance_prim_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_connaissance_sec_id">Connaissance secondaire</label>
						<select id="connaissance_connaissance_sec_id" name="connaissance_connaissance_sec_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_connaissances as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_connaissance_sec_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_voie_id">Voie</label>
						<select id="connaissance_voie_id" name="connaissance_voie_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_voies as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_voie_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_divin_id">Religion</label>
						<select id="connaissance_divin_id" name="connaissance_divin_id">
							<option>--- n.a. ---</option>
<?php
	foreach( $list_religions as $id => $infos ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_divin_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $infos[ "nom" ] ); ?></option>
<?php
	}
?>
						</select>
					</div>
				</div>
				<div>
					<input type="hidden" name="connaissance_id" value="<?php echo $connaissance->id; ?>" />
					<input type="submit" name="save_connaissance" value="Enregistrer" />
				</div>
			</form>
		</div>