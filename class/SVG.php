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
			$svg = '<div><h4>.' . $proteine->getId() . '</h3><br/><svg height="60" width="' . $svgWidth . '">
			<line x1="10" y1="30" x2="' . ($svgWidth - 10) . '" y2="30" style="stroke:black;stroke-width:2"/>';
			$svg .= "<rect width='" . $svgWidth . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>";
			for ($i = 150; $i < $length; $i += 150) {
				$svg .= '<line x1="' . (($i * $svgWidth) / $length) . '" y1="0" x2="' . (($i * $svgWidth) / $length) . '" y2="60" stroke="black" stroke-width="3" stroke-dasharray="5,5"/>';
			}
			foreach ($proteine->getDomains() as $key => $domain) {
				$color = substr($domain->getId(), 2);
				$color .= '0';
				$color = '#' . $color;

				$svg .= '<rect x="' . (($domain->getFirst() * $svgWidth) / $length)
					. '" y="15" width="' . ((($domain->getLast() * $svgWidth) / $length) - (($domain->getFirst() * $svgWidth) / $length))
					. '" height="30" rx="15" style="fill:' . $color . ';stroke:black;stroke-width:2"><title>' . $domain->getId()
					. '</title></rect>';
            //x domaine commence
            //width (domaine fini - domaine commence)
			}
		}
		else{
			$svg = '<div><h4>.' . $proteine->getId() . '</h3><br/><svg height="60" width="' . ($length+10) . '">
			<line x1="10" y1="30" x2="' . $length . '" y2="30" style="stroke:black;stroke-width:2"/>';
			$svg .= "<rect width='" . ($length+10) . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>";
			for ($i = 150; $i < $length; $i += 150) {
				$svg .= '<line x1="' . $i  . '" y1="0" x2="' . $i  . '" y2="60" stroke="black" stroke-width="3" stroke-dasharray="5,5"/>';
			}
			foreach ($proteine->getDomains() as $key => $domain) {
				$color = substr($domain->getId(), 2);
				$color .= '0';
				$color = '#' . $color;

				$svg .= '<rect x="' . $domain->getFirst()
					. '" y="15" width="' . ($domain->getLast()- $domain->getFirst())
					. '" height="30" rx="15" style="fill:' . $color . ';stroke:black;stroke-width:2"><title>' . $domain->getId()
					. '</title></rect>';
            //x domaine commence
            //width (domaine fini - domaine commence)
			}
		}


		$svg .= '</svg></div><br/><br />';
		echo $svg;
	}

}