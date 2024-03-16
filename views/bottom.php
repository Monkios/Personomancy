		<ul id="navigation">
<?php
	if( $logged_in && !$on_homepage ){
?>
			<li><a href="?s=user">Accueil</a></li>
<?php
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
	</body>
</html>