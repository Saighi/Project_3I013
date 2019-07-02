<?php
#Tools.php : Recharger les Classes et les fonctions utiles
require_once('class/Tools.php');
require_once('includes/lang.php');

/*Si données protéines à traiter (fichier rentrée dans le formulaire)
    => Page Affichage de Protéines */
if (isset($_FILES['fichier'])) {

    // Partie récupération des données et préférences utilisateur


    $fileProt = upload_file($_FILES["fichier"]); #On sauvegarde le fichier dans le serveur


    #Vérifier si la msie à échelle a été précisée (par GET ou POST) sinon initialiser à False
    $miseAEchelle = isset($_POST['miseAEchelle']) ? $_POST['miseAEchelle'] : (isset($_GET["miseAEchelle"]) ? $_GET["miseAEchelle"] : false);


    // Partie extraction données du fichier txt

    #On transforme les données du fichier en un objet ProteinsFromTxt
    $proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('data/proteines/' . $fileProt));

    $nbClusters = (!isset($_POST['nbClusters']) || $_POST['nbClusters'] < 1) ? 1 : round($_POST['nbClusters']);
    $listOfProteins = $proteinsFromTxt->getListOfProteins();
    $domainProperties = $proteinsFromTxt->getDomainsProperties();


    // Corps de la page

    $title = TITLE_SHOW;
    echo debut_html($title);
    echo "<body style='background-color:#F0F8FF'>\n";

    include('includes/navbar.html');

    #Créer l'objet Clusterer à partir de la liste de Protéines et d'un nombre de Classe déjà définie par l'indice de trie
    $clusterer = new Clusterer($listOfProteins, $nbClusters);
    #$clusters = un Tableau contenant des Groupes (tableaux) de Protéines.
    $clusters = $clusterer->getClusters();



    echo "<section class='section lb nopad spotlight style1'>

        <div class='content'>
        <br />     
        <iframe name='myframe' id='frame1' src='includes/ExtractExcel.php' style='width:0;height:0;border:0; border:none;'></iframe>
        <form method='POST' action='includes/ExtractExcel.php' target='myframe'>
        <input type='hidden' name='fileProt' value='" . $fileProt . "'/>
        <label for='name'> " . EXCEL_INPUT . " </label>
        <br />
        <input type='text' style = 'width:25%;'  class='form-control' id='name' name='name' value='" . $fileProt . "' required/>
        <input type='hidden' name='nbClusters' value='" . $nbClusters . "'/>
        <button type='submit' style='width:30%;' class='btn btn-success btn-lg btn-block'>" . EXTRACT_EXCEL . "</button>
        </form>";
    ?>
    <br /> <br />


    <?php

    afficher_clusters($clusters, $domainProperties, $miseAEchelle);

    #Fin page Affichage de protéines
    echo "</section>

    </div>
    </body></html>";
} else #Page Formulaire
{

    $title = PROTEINS_FORM;

    echo debut_html($title);

    ?>

    <body style="background-color:#F0F8FF">
        <?php
        include('includes/navbar.html');
        ?>
        <br />
        <?php
        include('includes/formulaire.html');
        include('includes/overfree.html');
        include('includes/spotlight.html');
        include('includes/footer.html');

        ?>

    </body>

    </html>
<?php
}
?>