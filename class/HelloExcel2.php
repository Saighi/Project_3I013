<?php
require_once('Tools.php');

$proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('../data/proteines/archs.txt'));
$listOfProteins = $proteinsFromTxt->getListOfProteins();
$clusterer = new Clusterer($listOfProteins, 46);

$domaine = new Domain(
    [
        "id" => "lol",
        "confiance" => '0',
        "first" => '3',
        "last" => '10'
    ]
);
$proteine = new Proteine([
    "id" => "ok",
    "taille" => 100000
]);
$proteine->addDomain($domaine);

$proteine2 = new Proteine([
    "id" => "ok",
    "taille" => 100000
]);
$proteine2->addDomain($domaine);


$clusterer = $clusterer->getClusters();

#$clusterer =[[$proteine,$proteine2]];

#var_dump($clusterer->getClusters());

include('HelloExcel.php');
if (getExcel($clusterer)) {
    echo 'ok';
} else {
    echo 'Excel générée.';
}
