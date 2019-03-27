<?php
class Orderer
{
    private $orderedListOfProteins;

    public function __construct($listOfProteins)
    {
        $this->setOrderedListOfProteins($listOfProteins);
    }

    public function setOrderedListOfProteins($listOfProteins)
    {
        $this->orderedListOfProteins[] = $listOfProteins[0];
        unset($listOfProteins[0]);
        while(count($listOfProteins)>0){
            $distancePrev = 9999;
            $prot= $this->orderedListOfProteins[count($this->orderedListOfProteins)-1];
            
            foreach ($listOfProteins as $id2 => $prot2) {
                
                $reversed = array_reverse($prot-> getDomains());
                $distanceReversed=$this->DistanceDeDamerauLevenshtein($reversed, $prot2->getDomains());
                $distanceNotReversed = $this->DistanceDeDamerauLevenshtein($prot-> getDomains(), $prot2->getDomains());
                $distance=($distanceNotReversed<$distanceReversed)? ($distanceNotReversed):($distanceReversed);
                
                if ($distance < $distancePrev) {
                    $idPlusProcheProt = $id2;
                    $distancePrev = $distance;
                }
            }
            
            $this->orderedListOfProteins[] = $listOfProteins[$idPlusProcheProt];
            unset($listOfProteins[$idPlusProcheProt]);
        }
    }

    public function DistanceDeDamerauLevenshtein($listeDomaine1, $listeDomaine2)
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

    // static public function af($d){
    //     foreach ($d as $k=>$v){
    //         foreach ($v as $ke=>$va){
    //             echo $va;
    //         }
    //         echo "</br>";
    //     }
    // }

    public function getOrderedListOfProteins()
    {
        return $this->orderedListOfProteins;
    }
}
