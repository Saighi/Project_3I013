<?php
require_once('Tools.php');

if (isset($_FILES["fichier"])) {
	$tmp_name = $_FILES["fichier"]["tmp_name"];
	$name = $_FILES["fichier"]["name"];
	move_uploaded_file($name, $tmp_name);
	$proteins = new ConverterfromTxt($_FILES["fichier"]["tmp_name"]);
	$title = "Protéines affichage";


	// affiche les infos des protéines
	/*foreach (preg_split("@(\s)+@",$proteins->getInfoProtein()) as $prot){
		echo $prot;
	}*/
	echo debut_html($title);
	echo "<body>\n";
	foreach ($proteins->getListOfProteins() as $prot) {

		if (isset($_POST['miseAEchelle'])) {
			SVG::show($prot, true);
		} else {
			SVG::show($prot, false);
		}

	}
	echo "</body></html>";


} else {

	$title = "Protéines formulaire";

	$body = "<body>" .
		"<form action='Test.php' method='post' enctype='multipart/form-data'><fieldset>\n" .
		"<label for id='nom'>Rentrez votre fichier ;) : </label>\n" .
		"<input type ='file' id='fichier' name='fichier' />\n" .
		"<label for id='nom2'>Mise à l'echelle : </label>\n" .
		"<input type ='checkbox' id='miseAEchelle' name='miseAEchelle' />\n" .
		"<input type ='submit' id='submit' name='submit' />\n" .
		"</fieldset></form></body></html>\n";
	echo debut_html($title) . $body;


}

?>