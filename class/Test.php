<?php
require_once ('Tools.php');
$tableau_de_prot=[];
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

foreach ($tableau_de_prot as $prot){
	SVG::show($prot);}
	?>

