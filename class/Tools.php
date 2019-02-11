<?php
function debut_html($title) {
	return
	  "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML Basic 1.1//EN'
		  'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd'>\n" .
	  "<html xmlns='http://www.w3.org/1999/xhtml' lang='fr'>\n" .
	  "<head>\n" .
	  "<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />\n" .
	  "<title>" . 
	  $title .
	  "</title>\n" .
	  "</head>\n";
}

function __autoload($className){
    $class = str_replace('_', '/', $className);
    require_once ''.$class.'.php';
}

function int_ok($n) {
	if(!is_int((int)$n)) {
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
		echo "<br/> taille =".$taille." domain first =".$domain->getFirst()." domain end =".$domain->getLast()."<br/>";
		throw new Exception('Le domaine dépasse la taille de la Protéine !');
	}
}

function proteine_ok($proteine) {
	if(!$proteine instanceof Proteine) {
		throw new Exception($proteine.' n\'est pas une Proteine !');
	}
}
?>