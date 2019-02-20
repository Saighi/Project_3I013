<?php
require_once 'Tools.php';

abstract class SVG
{
	private function __construct()
	{
	}

	public static function show($proteine, $domainParts, $miseAEchelle)
	{
		proteine_ok($proteine);

		$svgWidth = 1010; //taille de la protéine affichée
		$length = $proteine->getTaille();

		if ($miseAEchelle) {
			$taille = $svgWidth;
		} else {
			$taille = $length + 10;
		}

		$svg = '<tr><th>.' . $proteine->getId() . "</th>\n"
		."<td><svg height='60' width='" . $taille . "'>\n"
		."<line x1='10' y1='30' x2='" . ($taille - 10)."' y2='30' style='stroke:black;stroke-width:2'/>\n";
		$svg .= "<rect width='" . $taille . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>\n";

		if ($miseAEchelle) {
			for ($i = 150; $i < $length; $i += 150) {
				$svg .= '<line x1="' . (($i * $svgWidth) / $length) . '" y1="0" x2="' . (($i * $svgWidth) / $length) . '" y2="60" stroke="black" stroke-width="3" stroke-dasharray="5,5"/>';
			}
		}

		foreach ($proteine->getDomains() as $key => $domain) {
			if ($miseAEchelle) {
				$domainFirst = ($domain->getFirst() * $svgWidth) / $length;
				$domainLast = ($domain->getLast() * $svgWidth) / $length;
			}else{
				$domainFirst = $domain->getFirst();
				$domainLast = $domain->getLast();
			}
						$colorFile = file ('colors.txt');

			$color = substr($domain->getId(), 2);
			$color .= '0';
			$color = $colorFile[($domainParts[$domain->getId()]['random']*$color)%148];
			
			$lengthDomain = ($domainLast - $domainFirst);
			$nbPart=$domainParts[$domain->getId()]['nbParts'];

			$svg .= "<rect x='" . $domainFirst
					. "' y='0' width='" . $lengthDomain
					. "' height='60' style='fill:" . ($color) . ";stroke:black;stroke-width:0.5;fill-opacity: .5'></rect>\n";
					
			$fillPercentage= round(100/$nbPart);
			//on définit des "linearGradient" qui permettent à la base de faire des dégradés, dans notre cas
			//cela permet d'afficher plusieurs couleurs pour un rectangle
			$svg.= "<defs>\n<linearGradient id='MyGradient".$domain->getId()."' x2='100%' y2='0%'>\n";
			for($i=0;$i<$nbPart;$i++) {
				$svg.="<stop offset='".$fillPercentage."%' stop-color='".$colorFile[(substr($domain->getId(), 2)*($i+1)*$domainParts[$domain->getID()]['random'])%148]."' />\n";
			}
			$svg.= "</linearGradient>\n</defs>\n";

			//après avoir défini notre linear gradient on fait appel à lui dans la définition de notre rectangle par le biais de fill =fill="url(#MyGradient'.$domain->getId().')"
			$svg .= '<rect x="' . $domainFirst
					. '" y="15" width="' . $lengthDomain
					. '" height="30"  rx="10" fill="url(#MyGradient'.$domain->getId().')" stroke="black" stroke-width="2"><title>' . $domain->getId()
					. '</title></rect>';
			
            //x domaine commence
            //width (domaine fini - domaine commence)
		}


		$svg .= '</svg></td><tr>';
		echo $svg;
	}

}
