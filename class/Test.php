<?php
require_once ('Tools.php');
$proteine = new Proteine(['id' => 'Agaricus_bisporus_XP_006461865.1',
	'taille' => 2500]);
	
$domain = new Domain(['id' => 'PF00145',
	'confiance' => 9.16e-120,
	'first' => 272,
	'last' => 599]);
	
$proteine->addDomain($domain);
																							
																
$domain = new Domain(['id' => 'PF14634',
	'confiance' => 3.34e-08,
	'first' => 2052,
	'last' => 2088]);
	
$proteine->addDomain($domain);


$domain = new Domain(['id' => 'PF00271',
	'confiance' => 8.73e-09,
	'first' => 2145,
	'last' => 2245]);
	
$proteine->addDomain($domain);


$domain = new Domain(['id' => 'PF01485',
	'confiance' => 0.000158,
	'first' => 696,
	'last' => 729]);
	
$proteine->addDomain($domain);

$domain = new Domain(['id' => 'PF04851',
	'confiance' => 3.24e-12,
	'first' => 1253,
	'last' => 1388]);
	
$proteine->addDomain($domain);

$domain = new Domain(['id' => 'PF11875',
	'confiance' => 0.000771,
	'first' => 1425,
	'last' => 1510]);
	
$proteine->addDomain($domain);

$domain = new Domain(['id' => 'PF00350',
	'confiance' => 0.001,
	'first' => 1817,
	'last' => 1961]);
	
$proteine->addDomain($domain);


SVG::show($proteine);
	?>