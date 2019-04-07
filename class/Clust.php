<?php
require_once('Tools.php');

function cluster($individus,$nbClasses,$matrix) {
    #$classes : Array of Protein's array
    foreach($individus as $i) {
        $classes[][]=$i;
    }
    while (count($classes) > $nbClasses) {
        #Recherche du minimum des distances
        $i=0;
        $j=0;
        $distanceMin=$matrix[$i][$j];

        for ($k=1;$k<count($classes);$k++) {
            for($l=$k+1;$l<count($classes);$l++) {
                if($distanceMin>$matrix[$k][$l]) {
                    $i=$k;
                    $j=$l;
                    $distanceMin=$matrix[$i][$j];
                }
            }
        }
        echo "<br />i=$i";
        echo "<br />j=$j";
        #Fusion de classes[i] et classes[j] 
        if(isset($classes[$j])) {}
        foreach ($classes[$j] as $element) {
             $classes[$i][]=$element;
        }
        unset($classes[$j]);
    }
    return $classes;
}

$converterfromTxt = new ConverterfromTxt('proteines/archs.txt');
$listOfProteins=$converterfromTxt->getListOfProteins();

for($i=0;$i<count($listOfProteins);$i++) {
    for($j=0;$j<count($listOfProteins);$j++) {
            $matrix[$i][$j]=rand(0, 15);
    }
}
echo '<pre>';
echo var_dump(cluster($listOfProteins,2,$matrix));
echo '</pre>';

