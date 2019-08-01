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

    public function getCompactSize()
    {
        $myFile = "pfam27DomainName.txt";
        preg_match_all('/(.*)\t(.*)/', trim(file_get_contents($myFile)), $items);
        $quest = array_combine($items[1], $items[2]);

        $size=0;
        foreach($this->domains as $domain) {
            $size += strlen($quest[$domain->getID()]) * 15;
        }
        return $size;
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

    public function setDomains($domains)
    {
        return $this->domains=$domains;
    }

    public function cloneReverse()
    {
        return new Proteine([
        'id' => $this->id.'v2',
        'taille' => $this->taille,
        'domains' => array_reverse($this->domains)
        ]
    );
    }

    

}

