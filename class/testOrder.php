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
$V = new Domain(['id' => "V",
                        'confiance' => 0.3,
                        'first' => 2000,
                        'last' => 3000]);

$prot1->addDomain($a1);
$prot1->addDomain($b1);
$prot1->addDomain($c1);
$prot1->addDomain($V);

$prot2->addDomain($c1);
$prot2->addDomain($V);
$prot2->addDomain($b1);
$prot2->addDomain($a1);


$d = Orderer::DistanceDeDamerauLevenshtein($prot1,$prot2);

echo $d;


?>




