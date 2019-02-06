<?php
require_once ('Tools.php');

$proteins= new ConverterfromTxt('fichier_proteines.txt');
// affiche les infos des protéines
/*foreach (preg_split("@(\s)+@",$proteins->getInfoProtein()) as $prot){
	echo $prot;
}*/

foreach ($proteins->getListOfProteins() as $prot){
	SVG::show($prot);
	}
?>

