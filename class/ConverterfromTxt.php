<?php
require_once 'Tools.php';

class ConverterfromTxt{

	private $listOfProteins;// liste d'objet de type Proteine
	private $infoProtein;// String contenant les info des protéines, 
	//elle est initialisée en même temps que listOfProteins

	public function __construct(String $file)
    {
		$this->setListOfProteins($file);
	}
	
	public function setListOfProteins($file)
    {
		$this->listOfProteins=array();
		$this->infoProtein="";
		$buffer=[];
		/*Ouverture du fichier en lecture seule*/
		$handle = fopen($file, 'r');
		/*Si on a réussi à ouvrir le fichier*/
		if ($handle)
		{
			/*Tant que l'on est pas à la fin du fichier*/
			while (!feof($handle))
			{
				/*On lit la ligne courante*/
				$proteinAsAList= preg_split("@(\s)+@",trim(fgets($handle)));

				/*if ($buffer[sizeof($buffer)-1][sizeof($buffer[sizeof($buffer)-1])-1]==""){
					unset($buffer[sizeof($buffer)-1][sizeof($buffer[sizeof($buffer)-1])-1]);
				}*/
				$proteine = new Proteine(['id' => $proteinAsAList[0],
				'taille' => $proteinAsAList[1]]);
				$this->infoProtein .= " 'id' =>".$proteinAsAList[0];
				$this->infoProtein .= " 'taille' =>".$proteinAsAList[1];
				for($j=2; $j<sizeof($proteinAsAList);$j+=4){
					$this->infoProtein .= " 'id' =>".$proteinAsAList[$j];
					$this->infoProtein .= " confiance =>".$proteinAsAList[$j+1];
					$this->infoProtein .= " 'first' =>".$proteinAsAList[$j+2];
					$this->infoProtein .= " 'last' =>".(int)$proteinAsAList[$j+3];
					$domain = new Domain(['id' => $proteinAsAList[$j],
					'confiance' => $proteinAsAList[$j+1],
					'first' => $proteinAsAList[$j+2],
					'last' => $proteinAsAList[$j+3]]);
					$proteine->addDomain($domain);
				}
				($this->listOfProteins)[]=$proteine;
			}
			/*On ferme le fichier*/
			fclose($handle);
		}
    }

	public function getListOfProteins()
    {
        return $this->listOfProteins;
	}
	
	public function getInfoprotein()
    {
        return $this->infoProtein;
    }



}

