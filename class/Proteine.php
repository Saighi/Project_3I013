<?php
require_once 'Tools.php';

class Proteine
{
    use Hydrate;
    
    private $id;
    private $taille;
    private $domains;//array of Domain
    
    public function __construct(array $donnees)
    {
        $this-> refreshConstructor($donnees);
        
        
    }
    
	
    private function setId($id)
    {
        $this->id = $id;
    }
	
	public function getId()
    {
        return $this->id;
    }
	
    private function setTaille($taille)
    {
		int_ok($taille);
        $this->taille = $taille;
    }

    public function getTaille()
    {
        return $this->taille;
    }



    
    public function addDomain($domain)
    {
		domain_ok($domain);
		domain_taille_ok($domain,$this->taille);
        ($this->domains)[] = $domain;
		
    }
    
    public function getDomains()
    {
        return $this->domains;
    }

    

}

