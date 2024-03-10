<?php
	set_time_limit( 10 );
	$pdf = new TCPDF();
	$pdf->SetCreator( MANCY_NAME );
	$pdf->SetAuthor( "Pierre-Olivier A. Gauvin" );
	$pdf->SetTitle( "Les Chroniques d'Akéras - Feuille de personnage" );
	$pdf->SetSubject( $character_infos->nom );
	
	// Set Defaults
	$pdf->SetPrintHeader( FALSE );
	$pdf->SetPrintFooter( FALSE );
	$pdf->SetAutoPageBreak( FALSE );
	$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
	$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
	$pdf->setFontSubsetting( TRUE );
	$pdf->SetFillColor( PDF_FILL_COLOR );

	// --------------------------------------------------------- //
	$width_buffer  = 2;
	$width_total   = $pdf->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT;
	$width_half    = ( $width_total - $width_buffer ) / 2;
	$width_third   = ( $width_total - ( $width_buffer * 2 ) ) / 3;

	// PDF needs first page
	$pdf->AddPage();
	
	/* --- ENTÊTE DE LA PAGE --- */
	$pdf->SetFont( 'helvetica', 'B', 10 );
	$pdf->Cell( 0, 0, "Fiche de personnage - Les Chroniques d'Akéras", "", 1, "C", TRUE );
	$pdf->SetFont( "", "", 8 );
	$pdf->Cell( 22, 0, "Visitez ", "", 0, "R", TRUE );
	$pdf->SetFont( "", "U" );
	$pdf->SetTextColor( 0, 0, 255 );
	$pdf->Cell( 47, 0, "http://chroniquesdakeras.com/mancy/", "", 0, "C", TRUE );
	$pdf->SetFont( "", "" );
	$pdf->SetTextColor( 0 );
	$pdf->Cell( 0, 0, "pour créer votre personnage en ligne - obligatoire pour recevoir vos PX", "", 1, "L", TRUE );
	$pdf->Ln();
	
	$pdf->SetCellPaddings( 1, 1 );
	$pdf->SetFont( "", "", 9 );
	
	/* --- INFORMATIONS DE BASE --- */
	$pdf->Cell( 25, 0, "Nom du joueur : " );
	$pdf->Cell( $width_half - 25, 0, PdfEncode( $character_infos->joueur_nom ), "B" );
	$pdf->Cell( $width_buffer, 0, "" );
	$pdf->Cell( 32, 0, "Nom du personnage : " );
	$pdf->Cell( $width_half - 32, 0, PdfEncode( html_entity_decode( $character_infos->nom, ENT_QUOTES ) ), "B" );
	$pdf->Ln();
	
	$pdf->SetFont( "", "", 9 );
	$pdf->Cell( 25, 0, "Alignement : " );
	$pdf->Cell( $width_half - 25, 0, PdfEncode( $character_infos->alignement_nom ), "B" );
	$pdf->Cell( $width_buffer, 0, "" );
	$pdf->Cell( 32, 0, "Religion : " );
	$pdf->Cell( $width_half - 32, 0, PdfEncode( $character_infos->religion_nom ), "B" );
	$pdf->Ln();
	$pdf->Cell( $width_half + $width_buffer, 0, "" );
	$pdf->Cell( 32, 0, "Faction : " );
	$pdf->Cell( $width_half - 32, 0, PdfEncode( $character_infos->faction_nom ), "B" );
	$pdf->Ln();
	
	// Encadre les PV, PM, etc.
	$pdf->Rect( $pdf->GetX(), $pdf->GetY(),
			$width_third, ( $pdf->GetCellHeight( $pdf->GetFontSize() ) * 7.5 ) );
	
	/* --- STATISTIQUES DU PERSONNAGE --- */
	$pdf->Cell( $width_third, 0, "Statistiques", "LTR", 0, "", TRUE );
	$pdf->Cell( $width_buffer, 0, "" );
	$pdf->Cell( $width_third, 0, "Attributs", "", 0, "", TRUE );
	$pdf->Cell( $width_buffer, 0, "" );
	$pdf->Cell( $width_third, 0, "Race : " . PdfEncode( $character_infos->race_nom ), "", 0, "", TRUE );
	$pdf->Ln();
	
	$pdf->Cell( 25, 0, "P. de Vie : " );
	$pdf->Cell( $width_third - $width_buffer - 25, 0, $character_infos->GetPointsVieStr(), "B", 0, "C" );
	$pdf->Cell( $width_buffer * 2, 0, "" );
	$pdf->Cell( 25, 0, "Constitution" );
	AddSelections( $pdf, $character_infos->constitution );
	$pdf->Cell( $width_third + $width_buffer - 25, 0, "");
	if( count( $character_infos->capacites_raciales ) >= 1 ){
		$pdf->Cell( 12, 0, "CR 1 : " );
		$pdf->Cell( $width_third - 12, 0, PdfEncode( current( $character_infos->capacites_raciales ) ), "B" );
	}
	$pdf->Ln();
	
	$pdf->Cell( 25, 0, "P. de Magie : " );
	$pdf->Cell( $width_third - $width_buffer - 25, 0, $character_infos->GetPointsMagieStr(), "B", 0, "C" );
	$pdf->Cell( $width_buffer * 2, 0, "" );
	$pdf->Cell( 25, 0, "Spiritisme" );
	AddSelections( $pdf, $character_infos->spiritisme );
	$pdf->Cell( $width_third + $width_buffer - 25, 0, "");
	if( count( $character_infos->capacites_raciales ) >= 2 ){
		$pdf->Cell( 12, 0, "CR 2 : " );
		$pdf->Cell( $width_third - 12, 0, PdfEncode( next( $character_infos->capacites_raciales ) ), "B" );
	}
	$pdf->Ln();
	
	$pdf->Cell( 25, 0, "F. Magique : " );
	$pdf->Cell( $width_third - $width_buffer - 25, 0, $character_infos->GetForceMagique(), "B", 0, "C" );
	$pdf->Cell( $width_buffer * 2, 0, "" );
	$pdf->Cell( 25, 0, "Intelligence" );
	AddSelections( $pdf, $character_infos->intelligence );
	$pdf->Cell( $width_third + $width_buffer - 25, 0, "");
	if( count( $character_infos->capacites_raciales ) >= 3 ){
		$pdf->Cell( 12, 0, "CR 3 : " );
		$pdf->Cell( $width_third - 12, 0, PdfEncode( next( $character_infos->capacites_raciales ) ), "B" );
	}
	$pdf->Ln();
	
	$pdf->Cell( 25, 0, "S. Alerte : " );
	$pdf->Cell( $width_third - $width_buffer - 25, 0, $character_infos->GetSauvegardeAlerte(), "B", 0, "C" );
	$pdf->Cell( $width_buffer * 2, 0, "" );
	$pdf->Cell( 25, 0, "Alerte" );
	AddSelections( $pdf, $character_infos->alerte );
	$pdf->Cell( $width_third + $width_buffer - 25, 0, "" );
	if( count( $character_infos->capacites_raciales ) >= 4 ){
		$pdf->Cell( 12, 0, "CR 4 : " );
		$pdf->Cell( $width_third - 12, 0, PdfEncode( next( $character_infos->capacites_raciales ) ), "B" );
	}
	$pdf->Ln();
	
	$pdf->Cell( 25, 0, "S. Vigueur : " );
	$pdf->Cell( $width_third - $width_buffer - 25, 0, $character_infos->GetSauvegardeVigueurStr(), "B", 0, "C" );
	$pdf->Cell( $width_buffer * 2, 0, "" );
	$pdf->Cell( 25, 0, "Vigueur" );
	AddSelections( $pdf, $character_infos->vigueur );
	$pdf->Cell( $width_third + $width_buffer - 25, 0, "");
	if( count( $character_infos->capacites_raciales ) >= 5 ){
		$pdf->Cell( 12, 0, "CR 5 : " );
		$pdf->Cell( $width_third - 12, 0, PdfEncode( next( $character_infos->capacites_raciales ) ), "B" );
	}
	$pdf->Ln();
	
	$pdf->Cell( 25, 0, "S. Volonté : " );
	$pdf->Cell( $width_third - $width_buffer - 25, 0, $character_infos->GetSauvegardeVolonteStr(), "B", 0, "C" );
	$pdf->Cell( $width_buffer * 2, 0, "" );
	$pdf->Cell( 25, 0, "Volonté" );
	AddSelections( $pdf, $character_infos->volonte );
	$pdf->Cell( $width_third + $width_buffer - 25, 0, "");
	$pdf->Cell( 12, 0, "PX : " );
	$pdf->Cell( $width_third - 12, 0, $character_infos->px_restants . " / " . $character_infos->px_totaux, "B" );
	$pdf->Ln();

	// Conserve l'absisse pour y revenir après chaque colonne
	$pdf->Ln();
	$vertical_return = $pdf->GetY();
	
	/* --- COLONNE 1 - LISTE DES CAPACITÉS PAR VOIE --- */
	foreach( $list_voies as $voie_id => $voie_desc ){
		$voie_desc = PdfEncode( $voie_desc );
		$pdf->Cell( 25, 0, $voie_desc, "", 0, "", TRUE );
		if( $voie_id == $prestige_voie ){
			$pdf->Cell( $width_third - 25, 0, PdfEncode( $prestige_desc ), "", 0, "", TRUE );
		} else {
			$pdf->Cell( $width_third - 25, 0, "", "", 0, "", TRUE );
		}
		AddCheckBox( $pdf, $width_buffer, in_array( $voie_id, $character_infos->voies ) );
		$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
		$fontSize = $pdf->GetFontSizePt();
		$nb_capacites = 0;
		foreach( $list_capacites[ $voie_id ] as $capacite_id => $capacite_desc ){
			$nb_capacites++;
			$capacite_desc = PdfEncode( $capacite_desc );
			$nb_selections = 0;
			if( array_key_exists( $capacite_id, $character_infos->capacites ) ){
				$nb_selections = $character_infos->capacites[ $capacite_id ];
			}
			
			if( $pdf->GetStringWidth( $capacite_desc ) >= 24 ) {
				$pdf->SetFontSize( $fontSize - 1 );
				if( $pdf->GetStringWidth( $capacite_desc ) >= 24 ) {
					$pdf->SetFontSize( $fontSize - 2 );
				}
			}
			$pdf->Cell( 25, 0, $capacite_desc );
			AddSelections( $pdf, $nb_selections );
			$pdf->SetFontSize( $fontSize );
			$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
		}
		while( $nb_capacites < CAPA_MIN_NB ){
			$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
			$nb_capacites++;
		}
		$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
	}
	
	/* --- COLONNE 2 & 3 - CONNAISSANCES, SORTILLÈGES ET NOTES --- */
	$pdf->SetXY( PDF_MARGIN_LEFT + $width_third + $width_buffer, $vertical_return );
	$pdf->SetLeftMargin( PDF_MARGIN_LEFT + $width_third + $width_buffer );
	
	$current_elements_list = array();
	$current_desc_list = array();
	$nb_elements_showed = $min_nb_elements_showed = $elements_on_column = 0;
	$conn_base_showed = $conn_avc_showed = $conn_syn_showed = FALSE;
	$conn_mtr_showed = $conn_lgd_showed = $sorts_showed = FALSE;
	$on_second_column = FALSE;
	$safety = 0;
	
	// Boucle tant qu'il y a des choses à afficher
	do {
		$element_type_title = "";
		$element_desc = "";
		
		// Prêt à passer à la liste suivante
		if( count( $current_elements_list ) == 0 ){
			// Ajoute des lignes vides s'il n'y en avait pas assez
			while( $nb_elements_showed < $min_nb_elements_showed ){
				$pdf->Cell( $width_third, 0, "", "B" );
				$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
				
				$nb_elements_showed++;
				$elements_on_column++;
			}
			
			$nb_elements_showed = 0;
			
			// Choisi la liste à afficher
			if( !$conn_base_showed ){
				$element_type_title = "Connaissances de base";
				$min_nb_elements_showed = CONN_MIN_BASE;
				$current_elements_list = $character_infos->connaissances_base;
				$current_desc_list = $list_connaissances;
				$conn_base_showed = TRUE;
			} elseif( !$conn_avc_showed ){
				$element_type_title = "Connaissances avancées";
				$min_nb_elements_showed = CONN_MIN_AVANCEES;
				$current_elements_list = $character_infos->connaissances_avancees;
				$current_desc_list = $list_connaissances;
				$conn_avc_showed = TRUE;
			} elseif( !$conn_syn_showed ){
				$element_type_title = "Connaissances synergiques";
				$min_nb_elements_showed = CONN_MIN_SYNERGIQUES;
				$current_elements_list = $character_infos->connaissances_synergiques;
				$current_desc_list = $list_connaissances;
				$conn_syn_showed = TRUE;
			} elseif( !$conn_mtr_showed ){
				$element_type_title = "Connaissances de maître";
				$min_nb_elements_showed = CONN_MIN_MAITRE;
				$current_elements_list = $character_infos->connaissances_maitres;
				$current_desc_list = $list_connaissances;
				$conn_mtr_showed = TRUE;
			} elseif( !$conn_lgd_showed ){
				$element_type_title = "Connaissances légendaires";
				$min_nb_elements_showed = CONN_MIN_LEGENDAIRES;
				$current_elements_list = $character_infos->connaissances_legendaires;
				$current_desc_list = $list_connaissances;
				$conn_lgd_showed = TRUE;
			} elseif( !$sorts_showed ){
				$element_type_title = "Sortilèges & Recettes";
				$min_nb_elements_showed = SORTS_NB_MIN;
				$current_elements_list = $character_sorts;
				$current_desc_list = $list_sorts;
				$sorts_showed = TRUE;
			} else {
				// Sort de la boucle Do...While
				break;
			}
		}
		
		// Show the title if necessary
		if( $element_type_title != "" ){
			// Passe à la colonne suivante si nécessaire
			if( ( $elements_on_column + max( $min_nb_elements_showed, count( $current_elements_list ) ) + 2 ) >= ELEMENTS_PER_COLUMNS ){
				if( !$on_second_column ){
					for( $i = 0; $i < ELEMENTS_PER_COLUMNS - $elements_on_column - 1; $i++ ){
						$pdf->Cell( $width_third, 0, "", "B" );
						$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
					}
					
					$pdf->SetXY( PDF_MARGIN_LEFT + $width_third + $width_buffer + $width_third + $width_buffer, $vertical_return );
					$pdf->SetLeftMargin( PDF_MARGIN_LEFT + $width_third + $width_buffer + $width_third + $width_buffer );
				// Affichage sur la page suivante
				} else {
					$pdf->SetLeftMargin( PDF_MARGIN_LEFT );
					$pdf->AddPage();
				}
				
				$elements_on_column = 0;
				$nb_elements_showed = 0;
				$on_second_column = TRUE;
			}
			
			// Saut de ligne sauf pour la première de la colonne
			if( $elements_on_column > 0 ){
				$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
				$elements_on_column++;
			}
			
			// Affiche le titre
			$pdf->Cell( $width_third, 0, $element_type_title, "", 0, "", TRUE );
			$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
			
			$element_type_title = "";
			$elements_on_column++;
		}
		
		// Find element's name
		$element_id = array_shift( $current_elements_list );
		if( array_key_exists( $element_id, $current_desc_list ) ){
			$element_desc = PdfEncode( $current_desc_list[ $element_id ] );
			
			$pdf->Cell( $width_third, 0, $element_desc, "B" );
			$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
			
			$nb_elements_showed++;
			$elements_on_column++;
		}
		$safety++;
	} while( $safety < ( ELEMENTS_PER_COLUMNS * 2 ) );
	
	// Si ce n'est déjà fait, saute à la deuxième colonne
	if( !$on_second_column ){
		$pdf->SetXY( PDF_MARGIN_LEFT + $width_third + $width_buffer + $width_third + $width_buffer, $vertical_return );
		$pdf->SetLeftMargin( PDF_MARGIN_LEFT + $width_third + $width_buffer + $width_third + $width_buffer );
		$elements_on_column = 0;
	}
	$elements_on_column += 2;
	
	/* --- SECTION DES NOTES --- */
	if( $elements_on_column < ELEMENTS_PER_COLUMNS ){
		$pdf->Cell( $width_third, 0, "Notes", "", 0, "", TRUE );
		$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
		for( $i = 0; $i < ELEMENTS_PER_COLUMNS - $elements_on_column; $i++ ){
			$pdf->Cell( $width_third, 0, "", "B" );
			$pdf->Ln( $pdf->GetCellHeight( $pdf->GetFontSize() ) );
		}
	}

	// --------------------------------------------------------- //

	// Close and output PDF document inline
	$pdf->Output( "CdA_FdP_" . time() . ".pdf", "I" );

	// --------------------------------------------------------- //
	// --------------------------------------------------------- //
	
	function AddSelections( $pdf, $sel_checked ){
		$fontSize = $pdf->GetFontSize();
		$r = 1.2;
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		
		for( $i = 0; $i < 10; $i++ ){
			$cx = $x + ( $i * $r * 3 );
			$cy = $y + ( $fontSize + $r ) / 2;
			
			$pdf->Circle( $cx + $r, $cy, $r, 0, 360, ( $i < $sel_checked ) ? "FD" : "S" );
		}
	}
	
	function AddCheckBox( $pdf, $size, $checked ){
		$pdf->Rect(
				$pdf->GetX() - ( $size * 2.5 ),
				$pdf->GetY()+ ( $pdf->GetCellHeight( $pdf->GetFontSize() ) - $size ) / 2,
				$size,
				$size,
				"FD",
				array(),
				( $checked ? array( 0 ) : array( 255 ) )
		);
		$pdf->SetFillColor( PDF_FILL_COLOR );
	}
?>