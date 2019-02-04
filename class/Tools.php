<?php

function __autoload($className){
    $class = str_replace('_', '/', $className);
    require_once ''.$class.'.php';
}

function int_ok($n) {
	if(!is_int($n)) {
		throw new Exception($n.' n\'est pas un entier !');
	}
}

function domain_ok($domain) {
	if(!$domain instanceof Domain) {
		throw new Exception($domain.' n\'est pas un Domain !');
	}
}

function domain_taille_ok($domain,$taille) {
	domain_ok($domain);
	int_ok($taille);
	if($domain->getFirst() > $taille OR $domain->getLast() > $taille ) {
		throw new Exception('Le domaine '.$domain.' dépasse la taille de la Protéine !');
	}
}

function proteine_ok($proteine) {
	if(!$proteine instanceof Proteine) {
		throw new Exception($proteine.' n\'est pas une Proteine !');
	}
}
?>