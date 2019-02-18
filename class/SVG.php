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

		$svg = '<div><h4>.' . $proteine->getId() . '</h3><br/><svg height="60" width="' . $taille . '">
			<line x1="10" y1="30" x2="' . ($taille - 10) . '" y2="30" style="stroke:black;stroke-width:2"/>';
		$svg .= "<rect width='" . $taille . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>";

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
			$color = $colorFile[($domainParts[$domain->getID()]['random']*$color)%148];
			
			$lengthDomain = ($domainLast - $domainFirst);
			$nbPart=$domainParts[$domain->getID()]['nbParts'];

			$svg .= '<rect x="' . $domainFirst
					. '" y="0" width="' . $lengthDomain
					. '" height="60" style="fill:' . ($color) . ';stroke:black;stroke-width:0.5;fill-opacity: .5"></rect>';
					
		
			for($i=0;$i<$nbPart;$i++) {
				$svg .= '<rect x="' . ($domainFirst+($lengthDomain/$nbPart)*$i)
					. '" y="15" width="' . ($lengthDomain/$nbPart)
					. '" height="30" rx="5" style="fill:' . $colorFile[(substr($domain->getId(), 2)*($i+1)*$domainParts[$domain->getID()]['random'])%148] . ';stroke:black;stroke-width:2"><title>' . $domain->getId()
					. '</title></rect>';
			}
			
            //x domaine commence
            //width (domaine fini - domaine commence)
		}


		$svg .= '</svg></div><br/><br />';
		echo $svg;
	}

}
