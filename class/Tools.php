<?php

#Fonction pour import les classes
function __autoload($className)
{
	$class = str_replace('_', '/', $className);
	require_once '' . $class . '.php';
}

#les entêtes HTML
function debut_html($title)
{
	return
		"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML Basic 1.1//EN'
		  'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml' lang='fr'>\n
		<head>\n
		<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />\n
		<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>\n
		<script src='/Project_3I013/clickButton.js'></script>
		<title>
		$title
		</title>\n
		</head>";
}

#Fonction d'affichage de protéines
function afficher_proteines($listOfProteins, $domainsProperties, $nbPages, $protPerPages, $miseAEchelle, $nbClasses = 0)
{
	#Mode Clustering
	if ($nbClasses > 0) {
		$clusterer = new Clusterer($listOfProteins, $nbClasses);
		$protPerPages = count($listOfProteins); #pas de pagination
		$clusters = $clusterer->getClusters();
		$alerts = ['alert alert-primary', 'alert alert-warning']; #alterner couleurs entête groupe
		SVG::setDefs($domainsProperties); #initialiser les définitions des propriétés graphiques SVG
		#les groupes de protéines sont affichées sous forme de Tableau
		echo "<table class='table table-hover'>";
		#on parcourt les groupes
		foreach ($clusters as $indice => $groupe) {
			$countGrp = count($groupe);
			#entête du groupe
			echo "<tbody class='cluster'>
							<thead>
								<td colspan='2'>
									<div class='" . $alerts[$indice % 2] . "' role='alert' align='left'>
										<ul class='list-inline'>
											<li class='list-inline-item'>
												<h2>Groupe " . ($indice + 1) . "</h2>
											</li>
											<li class='list-inline-item'>
												<h5>$countGrp " . (($countGrp == 1) ? 'protéine' : 'protéines') . "</h5>
											</li>
											<li class='list-inline-item'>
													<button type='button'
														class='" . (($countGrp > 1) ? ' btn btn-primary' : 'btn btn-secondary') . "'
														onClick='javascript:onClick(this,$indice)' " . (($countGrp == 1) ? 'disabled' : '') . ">
															Show More
													</button>	
												</li>
										</ul>
									</div>
									</td>
							</thead>";
			#On parcourt les protéines du groupe
			foreach ($groupe as $indice => $prot) {
				#On l'affiche
				SVG::show($prot, $domainsProperties, $miseAEchelle);
				#Si nous avons affiché la première protéine, on masque les autres
				if ($indice == 0) {
					echo '<tbody class="proteinsToShow" style="display:none;">';
				}
				#Si c'est la dernière protéine, on arrête le masquage ici
				if ($indice == (count($groupe) - 1)) {
					echo "</tbody>";
				}
			}
		}
		#fin du tableau
		echo '</tbody> </table>';
	} else #Mode non Clustering
		{
			#on affiche un nombre $protPerPages de protéines pour la page $nbPages
			$nbPages--;
			foreach (array_slice($listOfProteins, $nbPages * $protPerPages, $protPerPages) as $prot) {
				SVG::show($prot, $domainsProperties, $miseAEchelle);
			}
		}
}

#Fonction pour enregistrer le fichier dans le serveur
function upload_file($file)
{
	$content_dir = 'data/proteines/'; // dossier où sera le fichier
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

#Fonctions utiles pour débuger
function int_ok($n)
{
	if (!is_int((int)$n)) {
		throw new Exception($n . ' n\'est pas un entier !');
	}
}

function domain_ok($domain)
{
	if (!$domain instanceof Domain) {
		throw new Exception($domain . ' n\'est pas un Domain !');
	}
}

function domain_taille_ok($domain, $taille)
{
	domain_ok($domain);
	int_ok($taille);
	if ($domain->getFirst() > $taille or $domain->getLast() > $taille) {
		echo "<br/> taille =" . $taille . " domain first =" . $domain->getFirst() . " domain end =" . $domain->getLast() . "<br/>";
		throw new Exception('Le domaine dépasse la taille de la Protéine !');
	}
}

function proteine_ok($proteine)
{
	if (!$proteine instanceof Proteine) {
		throw new Exception($proteine . ' n\'est pas une Proteine !');
	}
}
