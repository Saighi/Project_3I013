<?php
require_once('class/Tools.php');

if (isset($_GET['file']) || isset($_FILES['fichier'])) {
    if (isset($_FILES["fichier"])) {
        $name_file = upload_file($_FILES["fichier"]);
    }
    $fileProt = isset($_GET['file']) ? $_GET['file'] : $name_file;

    $title = "Protéines affichage";

    $proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('data/proteines/' . $fileProt));
    $nbProt = count($proteinsFromTxt->getListOfProteins());
    $nbProtPerPage = isset($_GET['nbProtPerPage']) ? $_GET['nbProtPerPage'] : (isset($_POST['nbProtPerPage']) ? htmlspecialchars($_POST['nbProtPerPage']) : 20);
    $nbProtPerPage = $nbProt < $nbProtPerPage ? $nbProt : $nbProtPerPage;
    $miseAEchelle = isset($_POST['miseAEchelle']) ? $_POST['miseAEchelle'] : (isset($_GET["miseAEchelle"]) ? $_GET["miseAEchelle"] : false);
    if(htmlspecialchars($_POST['nbClasses'])==0){
        $nbClasses=1;
    }else{
        $nbClasses = round($nbProt*htmlspecialchars($_POST['nbClasses']));
    }
    echo $nbClasses;
    echo debut_html($title);
    echo "<body>\n";
   /* echo "<TABLE BORDER='0'>\n";

    echo '<br /><div align="center">
            <FORM action="" method="GET">
                <label for="page">Page : </label>
                <SELECT id= "page" name="page" size="1" onchange="this.form.submit()">';
    for ($i = 0; $i < round($nbProt / $nbProtPerPage); $i++) {
        echo "<OPTION " . (($i == $_GET['page'] - 1) ? 'SELECTED' : '') . ">" . ($i + 1) . "</OPTION>";
    }
    echo '</SELECT> 
                <input type="hidden" name="file" value="' . $fileProt . '"/>
                <input type="hidden" name="nbProtPerPage" value="' . $nbProtPerPage . '"/>';
           if($miseAEchelle)
             echo '<input type="hidden" name="miseAEchelle" value="' . $miseAEchelle . '"/>';
    echo '</FORM>
        </div><br />';*/

    $listOfProteins = $proteinsFromTxt->getListOfProteins();
    $domainProperties = $proteinsFromTxt->getDomainsProperties();

    afficher_proteines($listOfProteins, $domainProperties, isset($_GET['page']) ? $_GET['page'] : 0, $nbProtPerPage, $miseAEchelle,$nbClasses);
    echo "</TABLE>\n</body></html>";
} else {

    $title = "Protéines Formulaire";

    $body = "<body>" .
        "<br />
        <div align='center'>
            <fieldset>
                <form action='' method='POST' enctype='multipart/form-data'>\n" .
        "<label for id='nom'>Rentrer votre fichier </label><br />\n" .
        "<input type ='file' id='fichier' name='fichier' required/>\n" .

       // "<br /><br /><label for id='nbProtPerPage'>Nb Proteines par Page : </label>\n" .
    //    "<input type ='text' id='nbProtPerPage' name='nbProtPerPage' value='99999999'/>\n" .
        "<br /><input type ='text' id='nbClasses' name='nbClasses' placeholder='indice de tri' required/>\n" .

        "<br /><label for id='miseAEchelle'>Mise à l'echelle : </label>\n" .
        "<input type ='checkbox' id='miseAEchelle' name='miseAEchelle' />\n" .

        "<br /><br /><input type ='submit' id='submit' name='submit' />\n" .
        "</form>
             </fieldset>
        </div>
        </body>
        </html>\n";
    echo debut_html($title) . $body;
}
