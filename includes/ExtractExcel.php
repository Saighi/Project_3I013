<?php
require_once('../class/Tools.php');
if(isset($_POST['fileProt']) && isset($_POST['name'])) {
$fname = htmlspecialchars($_POST['fileProt']);

$name = htmlspecialchars($_POST['name']);

$proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('../data/proteines/' . $_POST['fileProt']));
$listOfProteins = $proteinsFromTxt->getListOfProteins();
$clusterer = new Clusterer($listOfProteins, htmlspecialchars($_POST['nbClusters']));


$clusterer = $clusterer->getClusters();


include('Excel.php');
if (getExcel($clusterer, $name)) {
    echo 'Problème de chargement du fichier Excel';
} else {


    echo "<html>
            <head>
          <SCRIPT LANGUAGE='JavaScript'>
            window.location.href='excels/$name.xlsx'
                
            </SCRIPT>
                </head>
        </html>";
    }
}
