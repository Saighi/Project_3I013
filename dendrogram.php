<?php
require_once('class/Tools.php');

if (isset($_FILES["file"])) { #Si il a saisi un fichier par le formulaire
        $name_file = upload_file($_FILES["file"]); #On le sauvegarde dans le serveur
        $proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('data/proteines/' . $name_file));


        $listOfProteins = $proteinsFromTxt->getListOfProteins();
        $clusterer = new Clusterer($listOfProteins, 1);

        $clusterer->makeJSonDendrogram($name_file);
    
} else {
    echo 'Erreur: Fichier non rentré.';
}
