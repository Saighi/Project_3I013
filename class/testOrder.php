<?php
require_once 'Tools.php';
require 'Orderer.php';
require 'Constante.php';

$prot1 = new Proteine(['id' => "prot1",
  'taille' => 100000000]);
$prot2 = new Proteine(['id' => "prot2",
  'taille' => 100000000]);


$a1 = new Domain(['id' => "a",
                        'confiance' => 0.3,
                        'first' => 0,
                        'last' => 5]);
$b1 = new Domain(['id' => "b",
                        'confiance' => 0.3,
                        'first' => 500,
                        'last' => 1000]);

$c1 = new Domain(['id' => "c",
                        'confiance' => 0.3,
                        'first' => 2000,
                        'last' => 3000]);

$a2 = new Domain(['id' => "a",
                        'confiance' => 0.3,
                        'first' => 500,
                        'last' => 1000]);
$b2 = new Domain(['id' => "b",
                        'confiance' => 0.3,
                        'first' => 0,
                        'last' => 1]);

$c2 = new Domain(['id' => "c",
                        'confiance' => 0.3,
                        'first' => 2000,
                        'last' => 3000]);


$prot1->addDomain($a1);
$prot1->addDomain($b1);
$prot1->addDomain($c1);

$prot2->addDomain($a2);
$prot2->addDomain($b2);
$prot2->addDomain($c2);

$d = Orderer::DistanceDeDamerauLevenshtein($prot1,$prot2);
foreach($d as $key=>$value){
  foreach($value as $k=>$v){
    echo $v;
  }
  echo "</br>";
}

?>




