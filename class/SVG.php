<?php
require_once 'Tools.php';

abstract class SVG {
	private function __construct()
    {}
	
	public static function show($proteine) {
		proteine_ok($proteine);
		$svgWidth=$proteine->getTaille()+10;
		$length=$proteine->getTaille();
		
		$svg='<svg height="50" width="'.$svgWidth.'">
		<line x1="0" y1="20" x2="'.$length.'" y2="20" style="stroke:black;stroke-width:2"/>';
		foreach($proteine->getDomains() as $key => $domain) {
			$color=substr($domain->getId(),2);
			$color.='0';
			$color= '#'.$color;
			
			$svg.='<rect x="'.$domain->getFirst().'" y="0" width="'.($domain->getLast() - $domain->getFirst()).'" height="40" rx="15" style="fill:'.$color.';stroke:black;stroke-width:2"/>';
            //x domaine commence
            //width (domaine fini - domaine commence)
		}
		
		$svg.='</svg>';
		
		echo $svg;
	}
	
}