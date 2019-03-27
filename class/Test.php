<?php
require_once('Tools.php');
function afficher_proteines( $listOfProteins, $domainsParts,$nbPages, $protPerPages,$miseAEchelle)
{
    $nbPages--;

    foreach (array_slice($listOfProteins, $nbPages * $protPerPages, $protPerPages) as $prot) {
		if($miseAEchelle){
			SVG::show($prot, $domainsParts, true);
		}
		else{
			SVG::show($prot,$domainsParts, false);
		}
    }
}
if (isset($_GET['file']) || isset($_FILES['fichier'])) {
    if (isset($_FILES["fichier"])) {
            $content_dir = 'proteines/'; // dossier où sera déplacé le fichier

            $tmp_file = $_FILES['fichier']['tmp_name'];

            if (!is_uploaded_file($tmp_file)) {
                    exit("Le fichier est introuvable");
                }

            // on vérifie maintenant l'extension
            $type_file = $_FILES['fichier']['type'];

            if (!strstr($type_file, 'text/plain')) {
                    exit("Le fichier n'est pas un .txt mais un " . $type_file);
                }

            // on copie le fichier dans le dossier de destination
            $name_file = $_FILES['fichier']['name'];

            if (!move_uploaded_file($tmp_file, $content_dir . $name_file)) {
                    exit("Impossible de copier le fichier dans $content_dir");
                }
        }
    $fileProt = isset($_GET['file']) ? $_GET['file'] : $name_file;

    $converterfromTxt = new ConverterfromTxt(htmlspecialchars('proteines/' . $fileProt));
    $title = "Protéines affichage";
    $nbProt = count($converterfromTxt->getListOfProteins());
    $nbProtPerPage = isset($_POST['nbProtPerPage']) ? htmlspecialchars($_POST['nbProtPerPage']) : 15;
    $nbProtPerPage = isset($_GET['nbProtPerPage']) ? $_GET['nbProtPerPage'] : $nbProtPerPage;
	$nbProtPerPage = $nbProt < $nbProtPerPage ? $nbProt : $nbProtPerPage;
	$miseAEchelle=isset($_POST['miseAEchelle'])? $_POST['miseAEchelle']:(isset($_GET["miseAEchelle"])?$_GET["miseAEchelle"]:false);

    // affiche les infos des protéines
    /*foreach (preg_split("@(\s)+@",$converterfromTxt->getInfoProtein()) as $prot){
		echo $prot;
	}*/
    echo debut_html($title);
    echo "<body>\n";
    //echo $converterfromTxt->getInfoprotein()."\n";
    echo "<TABLE BORDER='0'>\n";

    // décomposer l'array de proteines en nbPerPage*Page,nbPerpage
    echo '<FORM action="" method="GET">
	<label for="page">Page : </label> <SELECT id= "page" name="page" size="1" onchange="this.form.submit()">';
    for ($i = 0; $i < round($nbProt / $nbProtPerPage); $i++) {
        echo "<OPTION " . (($i == $_GET['page'] - 1) ? 'SELECTED' : '') . ">" . ($i + 1) . "</OPTION>";
    }
    echo '</SELECT> 
	<input type="hidden" name="file" value="' . $fileProt . '"/>
	<input type="hidden" name="nbProtPerPage" value="' . $nbProtPerPage . '"/>
	<input type="hidden" name="miseAEchelle" value="' . $miseAEchelle . '"/>
    </FORM>';
    
    $listOfProteins=$converterfromTxt->getListOfProteins();
    $Orderer= new Orderer($listOfProteins);
    $listOfProteins=$Orderer->getOrderedListOfProteins();

    afficher_proteines( $listOfProteins,$converterfromTxt->getDomainParts() ,isset($_GET['page']) ? $_GET['page'] : 0, $nbProtPerPage,$miseAEchelle);
    echo "</TABLE>\n</body></html>";
} else {

    $title = "Protéines formulaire";

    $body = "<body>" .
        "<fieldset><center><form action='' method='post' enctype='multipart/form-data'>\n" .
        "<label for id='nom'>Rentrez votre fichier ;) : </label>\n" .
        "<input type ='file' id='fichier' name='fichier' />\n" .
        
		"<br /><br /><label for id='nbProtPerPage'>Nb Proteines par Page : </label>\n" .
        "<input type ='text' id='nbProtPerPage' name='nbProtPerPage' value='15'/>\n" .
        
		"<br /><label for id='miseAEchelle'>Mise à l'echelle : </label>\n" .
        "<input type ='checkbox' id='miseAEchelle' name='miseAEchelle' />\n" .
        
		"<br /><br /><input type ='submit' id='submit' name='submit' />\n" .
        "</fieldset></form></center></body></html>\n";
    echo debut_html($title) . $body;
}
 