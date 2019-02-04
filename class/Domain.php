<?php
require_once 'Tools.php';

class Domain
{
    use Hydrate;
    
    private $id;
    private $confiance;
    private $first;
    private $last;
    
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
	
    private function setConfiance($confiance)
    {
        $this->confiance = $confiance;
    }

    public function getConfiance()
    {
        return $this->confiance;
    }


    private function setFirst($first)
    {
		int_ok($first);
        $this->first = $first;
    }
	
	public function getFirst()
    {
        return $this->first;
    }
	
	private function setLast($last)
    {
		int_ok($last);
        $this->last = $last;
    }
	
	public function getLast()
    {
        return $this->last;
    }

    

}

