<?php
#Tools.php : Recharger les Classes et les fonctions utiles
require_once('class/Tools.php');

/*Si données protéines à traiter (formulaire pas encore saisie)
    => Page Affichage de Protéines */
if (isset($_GET['file']) || isset($_FILES['fichier'])) {

    // Partie récupération des données et préférences utilisateur

    /* Soit l'utilisateur souhaite accèder au traitement du fichier qu'il vient de saisir
        Soit il souhaite accèder à un fichier déjà existant dans le serveur */

    if (isset($_FILES["fichier"])) { #Si il a saisi un fichier par le formulaire
        $name_file = upload_file($_FILES["fichier"]); #On le sauvegarde dans le serveur
    }

    #Déterminer les données si ils sont rentré par l'utilisateur par un fichier ou si le fichier exist déjà dans le serveur
    $fileProt = isset($_GET['file']) ? $_GET['file'] : $name_file;
    #Vérifier si le nombre de protéines par page est précisée par l'utilisateur (soit par le lien(get), soit par le formulaire(post))
    #Sinon initialiser à 20
    $nbProtPerPage = isset($_GET['nbProtPerPage']) ? $_GET['nbProtPerPage'] : (isset($_POST['nbProtPerPage']) ? htmlspecialchars($_POST['nbProtPerPage']) : 20);
    #Vérifier si la msie à échelle a été précisée (par GET ou POST) sinon initialiser à False
    $miseAEchelle = isset($_POST['miseAEchelle']) ? $_POST['miseAEchelle'] : (isset($_GET["miseAEchelle"]) ? $_GET["miseAEchelle"] : false);


    // Partie extraction données du fichier txt

    #On transforme les données du fichier en un objet ProteinsFromTxt
    $proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('data/proteines/' . $fileProt));
    #Nombre total des protéines
    $nbProt = count($proteinsFromTxt->getListOfProteins());
    /* Si le nombre total de protéines est inférieur au nombre de protéines par page
        on affiche toutes les protéines dans une page
    */
    $nbProtPerPage = $nbProt < $nbProtPerPage ? $nbProt : $nbProtPerPage;
    /* Nombre de Classes
        Soit une seule : Mode sans Clustering (arbre coupée à l'indice 0)
            nbClasses = 1
        Soit plusieurs : Mode Clustering
            nbClasses = nombre de protéines * indice de coupe
    */
    $nbClasses = ($_POST['cutTree'] == 0) ? 1 : round($nbProt * htmlspecialchars($_POST['cutTree']));
    $listOfProteins = $proteinsFromTxt->getListOfProteins();
    $domainProperties = $proteinsFromTxt->getDomainsProperties();


    // Corps de la page

    $title = "Protéines affichage";
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

    
    afficher_clusters($listOfProteins, $domainProperties, $nbClasses,$miseAEchelle);

    #Fin page Affichage de protéines
    echo "</body></html>";
} else #Page Formulaire
    {

        $title = "Protéines Formulaire";

        $body = "<body>
                    <div align='center'>
                        <fieldset>
                            <form action='' method='POST' enctype='multipart/form-data'>\n
                                <label for id='nom'>Rentrer votre fichier </label><br />\n
                                <input type ='file' id='fichier' name='fichier' required/>\n" .

            // "<br /><br /><label for id='nbProtPerPage'>Nb Proteines par Page : </label>\n" .
            //    "<input type ='text' id='nbProtPerPage' name='nbProtPerPage' value='99999999'/>\n" .
            "<br />
                                <input type ='text' id='cutTree' name='cutTree' placeholder='indice de tri' required/>\n
                                <br />
                                <label for id='miseAEchelle'>Mise à l'echelle : </label>
                                <input type ='checkbox' id='miseAEchelle' name='miseAEchelle' />
                                <br /><br />
                                <input type ='submit' id='submit' name='submit' />
                                </form>
                         </fieldset>
                        </div>
                   </body>
              </html>";


        echo debut_html($title) . $body;
    }
