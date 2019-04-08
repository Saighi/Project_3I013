<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
require_once('Tools.php');


class Clusterer
{

    private $clusters; # Array of Protein's Array

    public function __construct($proteins, $nbClasses)
    {
        $this->setClusters($proteins, $nbClasses);
    }


    public function setClusters($proteins, $nbClasses)
    {
        # $classes : Array of Protein's array
        # $classe  : Array de Protéines
        foreach ($proteins as $i) {
            $classes[][] = $i;
        }
        while (count($classes) > $nbClasses) {

            #Calcul des dissimilarités entre classes dans une matrice triangulaire supérieure

            # $matDissim : Matrice = (count($classes) * count($classes))

            for ($i = 0; $i < count($classes); $i++) {
                for ($j = $i + 1; $j < count($classes); $j++) {
                    $matDissim[$i][$j] = $this->dissim($classes[$i], $classes[$j]);
                }
            }
            #Recherche du minimum des distances
            $i = 0;
            $j = 1;
            $distanceMin = $matDissim[$i][$j];

            for ($k = 1; $k < count($classes); $k++) {
                for ($l = $k + 1; $l < count($classes) - 1; $l++) {
                    if ($distanceMin > $matDissim[$k][$l]) {
                        $i = $k;
                        $j = $l;
                        $distanceMin = $matDissim[$i][$j];
                    }
                }
            }
            #Fusion de classes[i] et classes[j] 
            foreach ($classes[$j] as $element) {
                $classes[$i][] = $element;
            }

            array_splice($classes, $j, 1);
        }
        $this->clusters = $classes;
    }

    private function dissim($classe1, $classe2)
    {
        #Si chaque classe est composée d'un unique individu on renvoie la distance de Damerau-Levenshtein
        if (count($classe1) == count($classe2) && count($classe1) == 1) {
            return $this->DistanceDeDamerauLevenshtein($classe1[0]->getDomains(), $classe2[0]->getDomains());
        } else
            #Si non, les classes ont plusieurs proteins : nous calculons la distance par la moyenne des distances entre les proteins
            {
                $sum = 0;
                $nbLinks = count($classe1) * count($classe2);
                foreach ($classe1 as $prot1) {
                    foreach ($classe2 as $prot2) {
                        $sum += $this->DistanceDeDamerauLevenshtein($prot1->getDomains(), $prot2->getDomains());
                    }
                }

                return $sum / $nbLinks;
            }
    }

    private function DistanceDeDamerauLevenshtein($listeDomaine1, $listeDomaine2)
    {
        $sizeAlphabet = 0;
        $ids1 = array();
        $ids2 = array();
        $i = 1;
        foreach ($listeDomaine1 as $key => $domain) {
            $id = $domain->getId();
            if (!in_array($id, $ids1)) {
                $sizeAlphabet += 1;
            }
            $ids1[$i] = $id;
            $i++;
        }
        $i = 1;
        foreach ($listeDomaine2 as $key => $domain) {
            $id = $domain->getId();
            if (!in_array($id, $ids2) && !in_array($id, $ids1)) {
                $sizeAlphabet += 1;
            }
            $ids2[$i] = $id;
            $i++;
        }

        $ids1Length = count($ids1);
        $ids2Length = count($ids2);

        $d = array();

        for ($i = -1; $i <= $ids1Length; $i++) {
            $d[$i] = array();
        }

        $maxdist = $ids1Length + $ids2Length;

        $d[-1][-1] = $maxdist;

        for ($i = 0; $i <= $ids1Length; $i++) {
            $d[$i][-1] = $maxdist;
            $d[$i][0] = $i;
        }
        for ($i = 0; $i <= $ids2Length; $i++) {
            $d[-1][$i] = $maxdist;
            $d[0][$i] = $i;
        }

        $da = array();

        // Orderer::af($d);

        for ($i = 1; $i <= $ids1Length; $i++) {
            $db = 0;
            for ($j = 1; $j <= $ids2Length; $j++) {

                if (array_key_exists($ids2[$j], $da)) {
                    $k = $da[$ids2[$j]];
                } else {
                    $k = 0;
                }

                $l = $db;

                if ($ids1[$i] == $ids2[$j]) {
                    $cost = 0;
                    $db = $j;
                } else {
                    $cost = 1;
                }

                $d[$i][$j] = min($d[$i - 1][$j - 1] + $cost, $d[$i][$j - 1] + 1, $d[$i - 1][$j] + 1, $d[$k - 1][$l - 1] + ($i - $k - 1) + 1 + ($j - $l - 1));
            }
            $da[$ids1[$i]] = $i;
        }

        return $d[$ids1Length][$ids2Length];
    }
    public function getClusters() {
        return $this->clusters;
    }
}



/* TEST

$ProteinsFromTxt = new ProteinsFromTxt('../data/proteines/archs.txt');
$proteins = $ProteinsFromTxt->getListOfProteins();


echo '<pre>';
$clust = (new Clusterer ($proteins, round(count($proteins) / 2)))->getClusters();
foreach ($clust as $key => $group) {
    echo '<br /> groupe ' . $key . ' <br />';
    foreach ($group as $k => $v) {
        echo $k . '<br />';
    }
}
echo '</pre>';

*/