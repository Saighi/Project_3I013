<?php

function __autoload($className){
    $class = str_replace('_', '/', $className);
    require_once ''.$class.'.php';
}

function debut_html($title) {
	return
	  "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML Basic 1.1//EN'
		  'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd'>\n" .
	  "<html xmlns='http://www.w3.org/1999/xhtml' lang='fr'>\n" .
	  "<head>\n" .
		"<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />\n" .
		"<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>\n" .

	  "<title>" . 
	  $title .
	  "</title>\n" .
	  "</head>\n";
}

function afficher_proteines($listOfProteins, $domainsProperties, $nbPages, $protPerPages, $miseAEchelle,$nbClasses=0)
{
	if($nbClasses >0) {
		$clusterer = new Clusterer ($listOfProteins, $nbClasses);
		$protPerPages= count($listOfProteins);

		$clusters=$clusterer->getClusters();

		foreach($clusters as $key => $groupe ) {
			echo "</table><center><div><h2><b>Groupe $key</b></h2></div></center> <br /><table>";
			foreach($groupe as $prot) {
				if ($miseAEchelle) {
					SVG::show($prot, $domainsProperties, true);
				} else {
					SVG::show($prot, $domainsProperties, false);
				}
			}
		}
	}
	else
	{
		
    $nbPages--;

    foreach (array_slice($listOfProteins, $nbPages * $protPerPages, $protPerPages) as $prot) {
        if ($miseAEchelle) {
            SVG::show($prot, $domainsProperties, true);
        } else {
            SVG::show($prot, $domainsProperties, false);
        }
	}
	
}
}

function upload_file($file) {
	$content_dir = 'data/proteines/'; // dossier où sera déplacé le fichier

	$tmp_file = $file['tmp_name'];

	if (!is_uploaded_file($tmp_file)) {
			exit("Le fichier est introuvable");
	}

	// on vérifie maintenant l'extension
	$type_file = $file['type'];

	if (!strstr($type_file, 'text/plain')) {
			exit("Le fichier n'est pas un .txt mais un " . $type_file);
	}

	// on copie le fichier dans le dossier de destination
	$name_file = $file['name'];

	if (!move_uploaded_file($tmp_file, $content_dir . $name_file)) {
			exit("Impossible de copier le fichier dans $content_dir");
	}
	return $name_file;
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
