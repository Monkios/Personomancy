<?php
	function AddSelectionsPips( $label_str, $element_name, $current_value = 0, $current_xp = 0 ){
?>
						<div id="<?php echo $element_name; ?>_pips" class="as_label">
							<span><?php echo $label_str; ?> :</span>
<?php
		if( $current_value < 10 ){
			for( $s = 1; $s <= 10; $s++ ){
				$pip_status = "";
				$xp_cost = CharacterSheet::GetSelectionCompoundCost( $s, $current_value );
				$can_buy = $xp_cost <= $current_xp;
				
				$title = $s;
				if( $s == 1 ) $title .= "ère";
				else $title .= "e";
				$title .= " sélection";
				
				if( $s > $current_value ){
					$title .= " (" . $xp_cost . " XP)";
					
					if( !$can_buy ){
						$pip_status .= " disabled='disabled'";
					} else {
						$pip_status .= " onclick='this.form.submit();'";
					}
				} else {
					$pip_status .= " checked='checked' disabled='disabled'";
				}
?>
							<input type="checkbox" name="<?php echo $element_name; ?>" value="<?php echo $s; ?>" title="<?php echo $title; ?>"<?php echo $pip_status; ?> />
<?php
			}
		} else {
?>
							<select name="<?php echo $element_name; ?>" onchange="this.form.submit();">
<?php
			for( $s = 10; $s <= CHARACTER_MAX_CAPACITES_SELECTIONS; $s++ ){
				$option_status = "";
				$xp_cost = CharacterSheet::GetSelectionCompoundCost( $s, $current_value );
				$can_buy = $xp_cost <= $current_xp;
				
				$text = $s;
				if( $s == 1 ) $text .= "ère";
				else $text .= "e";
				$text .= " sélection";
				
				if( $s > $current_value ){
					if( $can_buy ){
						$text .= " (" . $xp_cost . " XP)";
					} else {
						break;
					}
				} elseif( $s == $current_value ) {
					$option_status .= " selected='selected'";
				} else {
					$option_status .= " disabled='disabled'";
				}
?>
								<option value="<?php echo $s; ?>"<?php echo $option_status; ?>><?php echo $text; ?></option>
<?php
			}
?>
							</select>
<?php
		}
?>
						</div>
<?php
	}
?>