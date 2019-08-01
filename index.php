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
 
    // execution du script python, la variable python_return contient les données concernant les protéines et leurs clusters respectifs
    #exec("python3 main* settings.txt sample.txt archs.txt perl", $python_return);

    // Partie extraction données du fichier txt
    #On transforme les données du fichier en un objet ProteinsFromTxt
    $proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('data/proteines/' . $fileProt));

    $nbClusters = (!isset($_POST['nbClusters']) || $_POST['nbClusters'] < 1) ? 1 : round($_POST['nbClusters']);
    $listOfProteins = $proteinsFromTxt->getListOfProteins();
    $domainProperties = $proteinsFromTxt->getDomainsProperties();


    // Corps de la page

    $title = TITLE_SHOW;
    echo debut_html($title);
    echo "<body style='background-color:white'>\n";

    include('includes/navbar.html');

    #Créer l'objet Clusterer à partir de la liste de Protéines et d'un nombre de Classe déjà définie par l'indice de trie
    $clusterer = new Clusterer($listOfProteins, $nbClusters);
    #$clusters = un Tableau contenant des Groupes (tableaux) de Protéines.
    $clusters = $clusterer->getClusters();


    SVG::setDefs($domainProperties);
    echo "		<section class='section overfree'>


    <div class='container'>

    <h2>".RESULT_PAGE."</h2>
  <p>SaEaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.  </p>
  <ul class='nav nav-pills'>
    <li class='active'><a data-toggle='pill' href='#home'>".NORMAL_VIEW."</a></li>
    <li><a data-toggle='pill' href='#menu1'>".COMPACT_VIEW."</a></li>
    <li><a data-toggle='pill' href='#menu2'>".STATISTICS."</a></li>
    <li><a data-toggle='pill' href='#menu3'>".EXPORT."</a></li>
  </ul>
  
  <div class='tab-content'>
    <div id='home' class='tab-pane fade in active'>
      <h3>".NORMAL_VIEW."</h3>";
      afficher_clusters($clusters, $domainProperties, $miseAEchelle);

echo "
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
    <div id='menu1' class='tab-pane fade'>
      <h3>".COMPACT_VIEW."</h3>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>";
      afficher_clusters_compact($clusters, $domainProperties, $miseAEchelle);

echo "      </div>
    <div id='menu2' class='tab-pane fade'>
      <h3>".STATISTICS."</h3>
      <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
    
    <div id='menu3' class='tab-pane fade'>
      <h3>".EXPORT."</h3>
      <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
      <br />     
      <iframe name='myframe' id='frame1' src='includes/ExtractExcel.php' style='width:0;height:0;border:0; border:none;'></iframe>
      <form method='POST' action='includes/ExtractExcel.php' target='myframe'>
      <input type='hidden' name='fileProt' value='" . $fileProt . "'/>
      <label for='name'> " . EXCEL_INPUT . " </label>
      <br />
      <input type='text' style = 'width:25%;'  class='form-control' id='name' name='name' value='" . $fileProt . "' required/>
      <input type='hidden' name='nbClusters' value='" . $nbClusters . "'/>
      <button type='submit' style='width:30%;' class='btn btn-success btn-lg btn-block'>" . EXTRACT_EXCEL . "</button>
      </form>
      </div>
  </div>


   ";
    ?>
    <br /> <br />


    <?php

    
    #Fin page Affichage de protéines
    echo "</div>
    </section>

    
    </body></html>";
} else #Page Formulaire
{

    $title = PROTEINS_FORM;

    echo debut_html($title);

    ?>

    <body style="background-color:white">
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