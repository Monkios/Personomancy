<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo LARP_NAME; ?> - <?php echo MANCY_NAME; ?></title>
		<link rel="stylesheet" type="text/css" href="./public/styles/normalize.css" />
		<link rel="stylesheet" type="text/css" href="./public/styles/base.css" />
		<link rel="stylesheet" type="text/css" href="./public/styles/character_sheet.css" />
	</head>
	<body>
		<h1><?php echo MANCY_NAME_ABBREV; ?> - V <?php echo MANCY_VERSION; ?></h1>
<?php
	if( $logged_in || !$on_homepage ){
?>
		<ul id="navigation">
<?php
		if( $logged_in ){
?>
			<li><a href="?s=user&a=logout">Déconnexion</a></li>
<?php
			if( !$on_homepage ){
?>
			<li><a href="?s=user">Accueil</a></li>
<?php
			} else {
?>
			<li><a href="?s=user&a=profile">Voir mon profil</a></li>
<?php
			}
		}
		
		if( isset( $nav_links ) ){
			foreach( $nav_links as $link_descr => $link_url ){
?>
			<li><a href="<?php echo $link_url; ?>"><?php echo $link_descr; ?></a></li>
<?php
			}
		}
?>
		</ul>
<?php
	}
	if( Message::MsgInQueue() > 0 ){
?>
		<ul id="messages_list">
<?php
		foreach( Message::GetQueue() as $msgs ){
?>
			<li><?php echo $msgs; ?></li>
<?php
		}
?>
		</ul>
<?php
	}
?>