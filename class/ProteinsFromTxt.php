<?php
require_once 'Tools.php';

class ProteinsFromTxt
{

    private $listOfProteins; // liste d'objet de type Proteine
    private $domainProperties; // array [id_domain => number_of_Parts,randomColor,randomForm,sens]
    //elle est initialisée en même temps que listOfProteins

    public function __construct(String $file)
    {
        $this->setListOfProteins($file);
        # $this->listOfProteins[]=$this->listOfProteins[0]->cloneReverse();
    }

    public function setListOfProteins($file)
    {
        $this->listOfProteins = array();
        $arrayDomNbMaxsize = array(); // array [ID_OF_DOMAIN => nb, maxSize ]
        $combinaison = array();

        /*Ouverture du fichier en lecture seule*/
        $handle = fopen($file, 'r');
        /*Si on a réussi à ouvrir le fichier*/
        if ($handle) {
            /*Tant que l'on est pas à la fin du fichier*/
            while (!feof($handle)) {
                /*On lit la ligne courante*/
                $proteinAsAList = preg_split("@(\s)+@", trim(fgets($handle)));
                if ($proteinAsAList[0] == "") {
                    break;
                }
                $proteine = new Proteine([
                    'id' => $proteinAsAList[0],
                    'taille' => $proteinAsAList[1]
                ]);
                for ($j = 2; $j < sizeof($proteinAsAList); $j += 4) {
                    $domain = new Domain([
                        'id' => $proteinAsAList[$j],
                        'confiance' => $proteinAsAList[$j + 1],
                        'first' => $proteinAsAList[$j + 2],
                        'last' => $proteinAsAList[$j + 3]
                    ]);

                    if (!isset($arrayDomNbMaxsize[$domain->getId()])) {
                        $arrayDomNbMaxsize[$domain->getId()]['nb'] = 0;
                        $arrayDomNbMaxsize[$domain->getId()]['maxSize'] = 0;
           
                    }

                    $arrayDomNbMaxsize[$domain->getId()]['nb']++;
                    if ($arrayDomNbMaxsize[$domain -> getId()]['maxSize'] < $proteinAsAList[$j + 3] - $proteinAsAList[$j + 2]) {
                        $arrayDomNbMaxsize[$domain->getId()]['maxSize'] = $proteinAsAList[$j + 3] - $proteinAsAList[$j + 2];
                    }
                    $proteine->addDomain($domain);
                }
                ($this->listOfProteins)[] = $proteine;
            }
            /*On ferme le fichier*/
            fclose($handle);
        }
        foreach ($arrayDomNbMaxsize as $id => $tab) {
            //la génération aléatoire boucle jusqu'a ce que on tombe sur une
            //combinaison (nombre de couleurs,couleurs,forme) qui ne soit pas utilisée.
            //le resultat est que, si on a pas assez de combinaisons possibles pour le nombre de domaine
            //on est amené à boucler à l'infini, à surveiller.
            do {
                $nbPart = round(1 / $tab['nb']) + 1;
                $randomColor = rand(1, 148);
                //initialisation de la forme aléatoire
                if ($tab['maxSize'] > 200) {
                    $randomForme = rand(0, 7);
                } else {
                    $randomForme = rand(0, 9);
                }
                $sens = rand(0, 1);
            } while (in_array(array($nbPart, $randomColor, $randomForme), $combinaison));
            $this->domainProperties[$id]['nbParts'] = $nbPart;
            $this->domainProperties[$id]['randomColor'] = $randomColor;
            $this->domainProperties[$id]['randomForme'] = $randomForme;
            $this->domainProperties[$id]['sens'] = $sens;
            //on stock les combinaisons au fur et à mesure dans un array.
            $combinaison[] = array($nbPart, $randomColor, $randomForme);
        }
        //var_dump($domainProperties);

    }

    public function getListOfProteins()
    {
        return $this->listOfProteins;
    }

    public function getDomainsProperties()
    {
        return $this->domainProperties;
    }
    public function getInfoprotein()
    {
        return $this->infoProtein;
    }
}
