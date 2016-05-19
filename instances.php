<?php

$sites = array(
	'heraldsun',
	'theaustralian',
	'perthnow',
	'dailytelegraph',
	'adelaidenow',
	'couriermail',
	'foxsports',
);

$list = array();
foreach( $sites as $site ) {
	$list[] = 'sit-' . $site;
	$list[] = 'uat-' . $site;
	$list[] = 'prod-' . $site;
	$list[] = 'ls-' . $site;
}

return $list;