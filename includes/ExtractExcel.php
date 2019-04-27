<?php
require_once('../class/Tools.php');
$fname=htmlspecialchars($_POST['fileProt']);

$name=htmlspecialchars($_POST['name']);

$proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('../data/proteines/' . $_POST['fileProt']));
$listOfProteins = $proteinsFromTxt->getListOfProteins();
$clusterer = new Clusterer($listOfProteins, htmlspecialchars($_POST['nbClasses']));


$clusterer = $clusterer->getClusters();


include('Excel.php');
if (getExcel($clusterer, $name)) {
    echo 'ok';
} else {
    header("Location: excels/$name.xlsx");
}
