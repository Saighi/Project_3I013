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
        foreach ($listOfProteins as $id => $prot) {
            $distancePrev = 9999;
            for ($i = $id + 1; $i < count($listOfProteins); $i++) {
                $distance = $this->DistanceDeDamerauLevenshtein($prot, $listOfProteins[$i]);
                if ($distance < $distancePrev) {
                    $plusProcheProt = $listOfProteins[$i];
                    $distancePrev = $distance;
                }
            }
            $this->orderedListOfProteins[] = $plusProcheProt;
        }
    }

    static public function DistanceDeDamerauLevenshtein($prot1, $prot2)
    {
        $sizeAlphabet = 0;
        $ids1 = array();
        $ids2 = array();
        $i = 1;
        foreach ($prot1->getDomains() as $key => $domain) {
            $id = $domain->getId();
            if (!in_array($id, $ids1)) {
                $sizeAlphabet += 1;
            }
            $ids1[$i] = $id;
            $i++;
        }
        $i = 1;
        foreach ($prot2->getDomains() as $key => $domain) {
            $id = $domain->getId();
            if (!in_array($id, $ids2) && !in_array($id, $ids1)) {
                $sizeAlphabet += 1;
            }
            $ids2[$i] = $id;
            $i++;
        }

        $da = array();
        $ids1Length= count($ids1);
        $ids2Length=count($ids2);

        for ($i = 1; $i <= $sizeAlphabet; $i++) {
            $da[$i] = 0;
        }

        $d = array();
        
        for ($i=-1;$i<=$ids1Length;$i++){
            $d[$i]= array();
        }
        
        $maxdist = $ids1Length + $ids2Length;

        $d[-1][-1]= $maxdist;
        
        for ($i = 0; $i<$ids1Length;$i++){
            $d[$i][-1] = $maxdist;
            $d[$i][0] = $i;
        }
        for ($i = 0; $i<$ids2Length;$i++){
            $d[-1][$i] = $maxdist;
            $d[0][$i] = $i;
        }

        for($i=1;$i<$ids1Length;$i++){
            $db=0;
            for($j=1;$j<$ids2Length;$j++){
            }
        }

        return $d;
    }

    public function getOrderedListOfProteins()
    {
        return $this->orderedlistOfProteins;
    }
}
