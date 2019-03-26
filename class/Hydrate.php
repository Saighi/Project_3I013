
<?php

trait Hydrate {
    public function hydrater(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set'.ucfirst($key);
            
            // Si le setter correspondant existe.
            if (method_exists($this, $method))
            {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }
    public function refreshConstructor(array $donnees)
    {
        $this->hydrater($donnees);
    }
}

