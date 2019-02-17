<?php
require_once 'Tools.php';

abstract class SVG
{
	private function __construct()
	{
	}

	public static function show($proteine, $miseAEchelle)
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
			$color = substr($domain->getId(), 2);
			$color .= '0';
			$color = '#' . $color;

			$svg .= '<rect x="' . $domainFirst
				. '" y="15" width="' . ($domainLast - $domainFirst)
				. '" height="30" rx="15" style="fill:' . $color . ';stroke:black;stroke-width:2"><title>' . $domain->getId()
				. '</title></rect>';
            //x domaine commence
            //width (domaine fini - domaine commence)
		}


		$svg .= '</svg></div><br/><br />';
		echo $svg;
	}

}
