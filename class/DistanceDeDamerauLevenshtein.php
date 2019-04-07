<?php
class DistanceDeDamerauLevenshtein
{
    private function __construct()
    { }
    
    public static function DistanceDeDamerauLevenshtein($listeDomaine1, $listeDomaine2)
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


}
