<?php
require_once ('Tools.php');
$tableau_de_prot=[];
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


$buffer=[];
/*Ouverture du fichier en lecture seule*/
$handle = fopen('fichier_proteines.txt', 'r');
/*Si on a réussi à ouvrir le fichier*/
if ($handle)
{
	/*Tant que l'on est pas à la fin du fichier*/
	while (!feof($handle))
	{
		/*On lit la ligne courante*/
		$buffer[]= preg_split("@(\s)+@",fgets($handle));
		if ($buffer[sizeof($buffer)-1][sizeof($buffer[sizeof($buffer)-1])-1]==""){
			unset($buffer[sizeof($buffer)-1][sizeof($buffer[sizeof($buffer)-1])-1]);
		}
		
		/*On l'affiche*/
	}
	
	/*On ferme le fichier*/
	fclose($handle);
}
$contenant=[];
foreach($buffer as $i){
	//echo "'id' =>".$i[0];
	//echo " taille =>".$i[1];
	$proteine = new Proteine(['id' => $i[0],
	'taille' => $i[1]]);
	
	for($j=2; $j<sizeof($i);$j+=4){
		//echo " 'id' =>".$i[$j];
		//echo " confiance =>".$i[$j+1];
		//echo " 'first' =>".$i[$j+2];
		//echo " 'last' =>".(int)$i[$j+3];
		$domain = new Domain(['id' => $i[$j],
		'confiance' => $i[$j+1],
		'first' => $i[$j+2],
		'last' => $i[$j+3]]);
		$proteine->addDomain($domain);
	}
	$tableau_de_prot[]=$proteine;


}

foreach($contenant as $t){
	foreach($t as $k => $v){
		echo "/".$k."=>".$v;
	}
	echo "///";
}

foreach ($tableau_de_prot as $prot){
	SVG::show($prot);}
	?>

