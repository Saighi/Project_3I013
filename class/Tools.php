<?php

define('ROOT_FOLDER', '/Project_3I013');
#Fonction pour importer les classes
function __autoload($className)
{
	$class = str_replace('_', '/', $className);
	require_once '' . $class . '.php';
}

#les entêtes HTML
function debut_html($title)
{
	return
		"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML Basic 1.1//EN' 'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd'>
	<!--[if IE 9]> <html class='no-js ie9 fixed-layout' lang='fr'> <![endif]-->
	<!--[if gt IE 9]><!-->
	<html xmlns='http://www.w3.org/1999/xhtml' lang='fr'>
	<!--<![endif]-->

	<head>

		<!-- Basic -->
		<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />\n


		<!-- Mobile Meta -->
		<meta name='viewport' content='width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>

		<!-- Site Meta -->
		<title>$title</title>
		<meta name='keywords' content=''>
		<meta name='description' content=''>
		<meta name='author' content=''>

		<!-- Site Icons -->
		<link rel='shortcut icon' href='images/favicon.ico' type='image/x-icon' />
		<link rel='apple-touch-icon' href='images/apple-touch-icon.png'>

		<!-- Google Fonts -->
		<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700' rel='stylesheet'>

		<!-- Custom & Default Styles -->
		<link rel='stylesheet' href='".ROOT_FOLDER."/css/font-awesome.min.css'>
		<link rel='stylesheet' href='".ROOT_FOLDER."/css/bootstrap.min.css'>
		<link rel='stylesheet' href='".ROOT_FOLDER."/css/animate.css'>
		<link rel='stylesheet' href='".ROOT_FOLDER."/css/carousel.css'>
		<link rel='stylesheet' href='".ROOT_FOLDER."/style.css'>
		<script src='".ROOT_FOLDER."/index.js'></script>\n
		<script src='http://d3js.org/d3.v4.js'></script>\n


		<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
		<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js'></script>

		<!--[if lt IE 9]>
			<script src='".ROOT_FOLDER."/js/vendor/html5shiv.min.js'></script>
			<script src='".ROOT_FOLDER."/js/vendor/respond.min.js'></script>
		<![endif]-->

	</head>";
}

#Normal View Fonction d'affichage des Groupes
function afficher_clusters($clusters, $domainsProperties, $miseAEchelle)
{
	#Pour alterner couleurs des entêtes de Groupe
	$alerts = ['alert alert-success', 'alert alert-info'];
	#Initialiser la définition des propriétés graphiques SVG
	//SVG::setDefs($domainsProperties);

	#Affichage du Tableau Clusters
	echo "<table class='table table-hover'>";
	#On parcourt les groupes
	$nbClusters= count($clusters);
	foreach ($clusters as $indice => $groupe) {
		$countGrp = count($groupe);
		/* Entête du Groupe :
			Liste Totale (si un unique cluster) | Groupe {numéro}
			{nombre} Protéine(s)
			{Boutton {Show More | Hidden } }
		*/
		echo "<tbody class='cluster'>
							<thead>
								<td colspan='2'>
									<div class='" . $alerts[$indice%2] . "' role='alert' align='left'>
										<ul class='list-inline'>
											<li class='list-inline-item'>
												<h2>".( ($nbClusters==1)?TOTAL_LIST:GROUP.' ' . ($indice + 1) ) ."</h2>
											</li>
											<li class='list-inline-item'>
												<h5>$countGrp " . (($countGrp == 1) ? PROTEIN : PROTEINS) . "</h5>
											</li>
											<li class='list-inline-item'>
													<button type='button'
														class='" . (($countGrp > 1) ? ' btn btn-primary' : 'btn btn-secondary') . "'
														onClick='javascript:onClick(this,$indice)' " . (($countGrp == 1) ? 'disabled' : '') . ">
															Show More
													</button>	
												</li>
												<div class='pages' style='display:none;'>
													<p class='text'></p>
													<input type='number' class='inputNbProt'>
													&emsp;&emsp;&emsp;
													<select class='selector'></select>
												</div>
										</ul>
									</div>
									</td>
							</thead>";
		#On parcourt les Protéines du Groupe
		foreach ($groupe as $indice => $prot) {
			#On affiche une ligne du Table représentant la protéine
			//echo ($indice == 0) ? '<tr style="outline: medium solid">' : '<tr>'; #mettre bordure si premiere protéine
			echo '<tr>';
			SVG::show($prot, $domainsProperties, $miseAEchelle);
			echo '</tr>';
			#Si nous avons affiché la première protéine du groupe, on masque les autres
			if ($indice == 0) {
				echo '<tbody class="proteinsToShow" style="display:none;">';
			}
			#Si c'est la dernière protéine du groupe, on arrête le masquage ici
			if ($indice == (count($groupe) - 1)) {
				echo "</tbody>";
			}
		}
		#fin du groupe
		echo '</tbody>';
	}
	#fin du tableau clusters
	echo '</table>';
}

// Compact View
#Fonction d'affichage des Groupes
function afficher_clusters_compact($clusters, $domainsProperties, $miseAEchelle)
{
	#Pour alterner couleurs des entêtes de Groupe
	$alerts = ['alert alert-success', 'alert alert-info'];
	#Initialiser la définition des propriétés graphiques SVG
//	SVG::setDefs($domainsProperties);

	#Affichage du Tableau Clusters
	echo "<table class='table table-hover'>";
	#On parcourt les groupes
	$nbClusters= count($clusters);
	foreach ($clusters as $indice => $groupe) {
		$countGrp = count($groupe);
		/* Entête du Groupe :
			Liste Totale (si un unique cluster) | Groupe {numéro}
			{nombre} Protéine(s)
			{Boutton {Show More | Hidden } }
		*/
		echo "<tbody class='cluster'>
							<thead>
								<td colspan='2'>
									<div class='" . $alerts[$indice%2] . "' role='alert' align='left'>
										<ul class='list-inline'>
											<li class='list-inline-item'>
												<h2>".( ($nbClusters==1)?TOTAL_LIST:GROUP.' ' . ($indice + 1) ) ."</h2>
											</li>
											<li class='list-inline-item'>
												<h5>$countGrp " . (($countGrp == 1) ? PROTEIN : PROTEINS) . "</h5>
											</li>
										</ul>
									</div>
									</td>
							</thead>";
		#On parcourt les Protéines du Groupe
		foreach ($groupe as $indice => $prot) {
			#On affiche une ligne du Table représentant la protéine
			//echo ($indice == 0) ? '<tr style="outline: medium solid">' : '<tr>'; #mettre bordure si premiere protéine
			echo '<tr>';
			SVG::show_compact($prot, $domainsProperties, $miseAEchelle);
			echo '</tr>';
		}
		#fin du groupe
		echo '</tbody>';
	}
	#fin du tableau clusters
	echo '</table>';
}



function afficher_top20_archs($top20archs, $domainsProperties)
{
	#Pour alterner couleurs des entêtes de Groupe
	$alerts = 'alert alert-info';
	#Initialiser la définition des propriétés graphiques SVG
//	SVG::setDefs($domainsProperties);

	#Affichage du Tableau Clusters
	echo "<table class='table table-hover'>";
	#On parcourt les groupes
		/* Entête du Groupe :
			Liste Totale (si un unique cluster) | Groupe {numéro}
			{nombre} Protéine(s)
			{Boutton {Show More | Hidden } }
		*/
		echo "<tbody class='cluster'>
							<thead>
								<td colspan='2'>
									<div class='" . $alerts[0] . "' role='alert' align='left'>
										<ul class='list-inline'>
											<li class='list-inline-item'>
												<h2>Most seen architecture</h2>
											</li>
											<li class='list-inline-item'>
												<h5>TOP 20</h5>
											</li>
										</ul>
									</div>
									</td>
							</thead>";
		#On parcourt les Protéines du Groupe
		foreach ($top20archs as $arch) {
			#On affiche une ligne du Table représentant la protéine
			//echo ($indice == 0) ? '<tr style="outline: medium solid">' : '<tr>'; #mettre bordure si premiere protéine
			echo '<tr>';
			SVG::show_arch($arch, $domainsProperties, false);
			echo '</tr>';
		}
		#fin du groupe
		echo '</tbody>';
	
	#fin du tableau clusters
	echo '</table>';
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
		echo "<br /> taille =" . $taille . " domain first =" . $domain->getFirst() . " domain end =" . $domain->getLast() . "<br />";
		throw new Exception('Le domaine dépasse la taille de la Protéine !');
	}
}

function proteine_ok($proteine)
{
	if (!$proteine instanceof Proteine) {
		throw new Exception($proteine . ' n\'est pas une Proteine !');
	}
}
