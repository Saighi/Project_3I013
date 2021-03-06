<?php
require_once 'Tools.php';
define('WITH', 1010);

# Cette classe génère l'ensemble du code SVG utilisé dans notre site web

abstract class SVG
{
    private function __construct()
    { }

    # Cette fonction affiche le code SVG donnant les définitions des couleurs remplissant nos domaines.
    # Les différentes formes représentant les domaines n'auront plus qu'à faire appel à 
    # ces définitions pour gérer leur remplissage.
    # les couleurs sont choisies en fonction d'un paramètre aléatoire dont la valeur est décrétée 
    # dans ProteinsFromTxt et se trouve dans $domainProperties.
    # Cette fonction doit être exécutée avant de commencer l'affichage des protéines. 

    public static function setDefs($domainProperties)
    {
        $def = "<svg height='0' width='0'>";

        foreach ($domainProperties as $id => $value) {
            $colorFile = file('colors.txt');
            $nbPart = $value['nbParts'];
            $fillPercentage = round(100 / $nbPart);
            //on définit des "linearGradient" qui permettent à la base de faire des dégradés, dans notre cas
            //cela permet d'afficher plusieurs couleurs pour un rectangle
            if ($value['sens'] == 0) {
                $def .= "<defs>\n<linearGradient id='MyGradient" . $id . "' x2='100%' y2='0%'>\n";
            } else {
                $def .= "<defs>\n<linearGradient id='MyGradient" . $id . "' x2='0%' y2='100%'>\n";
            }
            for ($i = 0; $i < $nbPart; $i++) {
                $def .= "<stop offset='" . $fillPercentage . "%' stop-color='#" . trim($colorFile[(substr($id, 2) * ($i + 1) * $value['randomColor']) % 148]) . "'></stop>\n";
            }
            $def .= "</linearGradient>\n</defs>\n";
        }
        echo $def . "</svg>";
    }

    # Fonction se chargeant de l'affichage des protéines.
    public static function show($proteine, $domainProperties, $miseAEchelle)
    {

        proteine_ok($proteine);
        $svgWidth = WITH; //longueur de la protéine affichée
        $length = $proteine->getTaille();

        if ($miseAEchelle) {
            $taille = $svgWidth;
        } else {
            $taille = $length + 10;
        }


        $svg = '<th>' . $proteine->getId() . "<br /><br />\n"
            . "<svg height='84' width='" . ($taille + 20) . "'>\n"
            . "<line x1='10' y1='42' x2='" . ($taille - 10) . "' y2='42' style='stroke:black;stroke-width:2'/>\n";

        //$svg .= "<rect width='" . $taille . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>\n";

        for ($i = 150; $i < $length; $i += 150) {
            if ($miseAEchelle) {
                $svg .= "<text x='" . ((($i * $svgWidth) / $length) - 30) . "' y='12'>" . $i . "</text>\n"; // Affiche le début de chaque domaines au dessus de chaque domaines
                $svg .= '<line x1="' . (($i * $svgWidth) / $length) . '" y1="14" x2="' . (($i * $svgWidth) / $length) . '" y2="74" stroke="black" stroke-width="3" stroke-dasharray="5,5"/>';
            } else {
                $svg .= "<text x='" . $i . "' y='12'>" . $i . "</text>\n"; // Affiche le début de chaque domaines au dessus de chaque domaines
                $svg .= '<line x1="' . $i . '" y1="12" x2="' . $i . '" y2="72" stroke="black" stroke-width="3" stroke-dasharray="5,5"/>';
            }
        }

        foreach ($proteine->getDomains() as $domain) {
        
                $domainFirst = $domain->getFirst();
                $domainLast = $domain->getLast();
            

            $lengthDomain = ($domainLast - $domainFirst);
            //Choix de la forme :
            $svg .= SVG::choice($domainProperties[$domain->getID()]['randomForme'], $domainFirst, $lengthDomain, $domain->getID(), $domain->getConfiance(),$miseAEchelle,$svgWidth,$length);
            //x domaine commence
            //width (domaine fini - domaine commence)
        }
        $svg .= '</svg></th>';
        echo $svg;
    }

    # Fonction se chargeant de l'affichage des protéines.
    public static function show_compact($proteine, $domainProperties, $miseAEchelle)
    {

        $myFile = "pfam27DomainName.txt";
        preg_match_all('/(.*)\t(.*)/', trim(file_get_contents($myFile)), $items);
        $quest = array_combine($items[1], $items[2]);

        proteine_ok($proteine);
        $svgWidth = WITH*0.7; //longueur de la protéine affichée
        $length = $proteine->getCompactSize();

       
            $taille = $length + 10;
        


        $svg = '<th>' . $proteine->getId() . "</th>\n"
            . "<td><svg height='50' width='" . ($taille + 20) . "'>\n";
           
        //$svg .= "<rect width='" . $taille . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>\n";



        $i = 10;

        foreach ($proteine->getDomains() as $domain) {

            $domainFirst = $i;
            $domainLast  = $i + strlen($quest[$domain->getID()]) * 15;


            $lengthDomain = ($domainLast - $domainFirst);
            //Choix de la forme :
            $svg .= SVG::choice_compact($domainProperties[$domain->getID()]['randomForme'], $domainFirst, $lengthDomain, $domain->getID(), $domain->getConfiance(), $miseAEchelle, $svgWidth, $length);

            $svg .= "<text x='" . ($domainFirst + round($lengthDomain / 6)) . "' y='27'  style='font-weight:bold;'>" . $quest[$domain->getID()] . "</text>\n"; // Affiche le début de chaque domaines au dessus de chaque domaines


            //x domaine commence
            //width (domaine fini - domaine commence)
            $i = $domainLast;
        }
        $svg .= '</svg></td>';
        echo $svg;
    }


    public static function show_arch($arch, $domainProperties, $miseAEchelle)
    {

        $myFile = "pfam27DomainName.txt";
        preg_match_all('/(.*)\t(.*)/', trim(file_get_contents($myFile)), $items);
        $quest = array_combine($items[1], $items[2]);

        $svgWidth = WITH*0.7; //longueur de la protéine affichée

       // $length = $proteine->getCompactSize();
$length =0;
foreach($arch['domains'] as $domain) {
    $length += strlen($quest[$domain->getID()]) * 15;
}
       
            $taille = $length + 10;
        


        $svg =  "<th>Seen : ".$arch['nb']."</th>".
        "<td><svg height='50' width='" . ($taille + 20) . "'>\n";
           
        //$svg .= "<rect width='" . $taille . "' height='60' style='fill:rgb(0,0,0);stroke-width:3' fill-opacity='0.1'/>\n";



        $i = 10;

        foreach ($arch['domains'] as $domain) {

            $domainFirst = $i;
            $domainLast  = $i + strlen($quest[$domain->getID()]) * 15;


            $lengthDomain = ($domainLast - $domainFirst);
            //Choix de la forme :
            $svg .= SVG::choice_compact($domainProperties[$domain->getID()]['randomForme'], $domainFirst, $lengthDomain, $domain->getID(), $domain->getConfiance(), $miseAEchelle, $svgWidth, $length);

            $svg .= "<text x='" . ($domainFirst + round($lengthDomain / 6)) . "' y='27'  style='font-weight:bold;'>" . $quest[$domain->getID()] . "</text>\n"; // Affiche le début de chaque domaines au dessus de chaque domaines


            //x domaine commence
            //width (domaine fini - domaine commence)
            $i = $domainLast;
        }
        $svg .= '</svg></td>';
        echo $svg;
    }

    # Fonction rsponsable du choix de forme et fonction d'un paramètre aléatoire
    # dont la valeur est décrétée 
    # dans ProteinsFromTxt et se trouve dans $domainProperties.
    private static function choice($randomForm, $domainFirst, $lengthDomain, $domainId, $domainConfiance,$miseAEchelle,$svgWidth,$length)
    {
        
        $domainStart=$domainFirst;
        $realLength = $lengthDomain;
        if ($miseAEchelle) {
            $domainFirst = ($domainFirst * $svgWidth) / $length;
            $lengthDomain = ($lengthDomain * $svgWidth) / $length;
        } 
        // alpha est le facteur de correction de la taille du domaine, ainsi si un domaine est trop petit pour être affiché avec une certaine forme
        // il est élargi d'un facteur alpha correspondant à la largeur necessaire (20 ici).
        if (($lengthDomain) < 20) {
            $alpha = (20 - $lengthDomain) / 2;
        } else {
            $alpha = 0;
        }
        switch ($randomForm) {
                //rectangle sans arrondi:
            case 0:
                $forme = "<rect x='" . $domainFirst
                    . "' y='27' width='" . $lengthDomain
                    . "' height='30'  fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></rect>\n";

                break;
                //rectangle avec arrondi:
            case 1:
                //après avoir défini notre linear gradient on fait appel à lui dans la définition de notre rectangle par le biais de fill =fill="url(#MyGradient'.$domain->getId().')"
                $forme = "<rect x='" . $domainFirst
                    . "' y='27' width='" . $lengthDomain
                    . "' height='30'  rx='10' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></rect>\n";

                break;
                //Hexagone
            case 2:
                $forme = "<polygon points='" . (($domainFirst + 10) - $alpha) . ",27 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",27 " . (($domainFirst + $lengthDomain) + $alpha) . ",42 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",57 " . (($domainFirst + 10) - $alpha) . ",57 " . (($domainFirst) - $alpha) . ",42' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Rectangle aux bords droit et gauche en courbe vers l'intérieur :
            case 3:
                $forme = "<path d='M" . ($domainFirst - $alpha) . " 27 h " . ($lengthDomain + $alpha) . " q-15,15 0,30 h -" . ($lengthDomain + $alpha) . " q15,-15 0,-30 'fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></path>\n";
                break;
                //Noeud papillon:
            case 4:
                $forme = "<polygon points='" . $domainFirst . ",27 " . ($domainFirst + $lengthDomain / 2) . ",32 " . ($domainFirst + $lengthDomain) . ",27 " . ($domainFirst + $lengthDomain) . ",57 " . ($domainFirst + $lengthDomain / 2) . ",52 " . $domainFirst . ",57' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Enclume inversée :
            case 5:
                $forme = "<polygon points='" . (($domainFirst + 10) - $alpha) . ",27 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",27 " . (($domainFirst + $lengthDomain) + $alpha) . ",57 " . ($domainFirst - $alpha) . ",57' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Enclume :
            case 6:
                $forme = "<polygon points='" . ($domainFirst - $alpha) . ",27 " . (($domainFirst + $lengthDomain) + $alpha) . ",27 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",57 " . (($domainFirst + 10) - $alpha) . ",57' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Flêche :
            case 7:
                $forme = "<polygon points='" . ($domainFirst - $alpha) . ",27 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",27 " . (($domainFirst + $lengthDomain) + $alpha) . ",42 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",57 " . ($domainFirst - $alpha) . ",57 " . (($domainFirst + 10) - $alpha) . ",42' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Losange:
            case 8:
                $forme = "<polygon points='" . $domainFirst . ",42 " . ($domainFirst + ($lengthDomain / 2)) . ",27 " . ($domainFirst + $lengthDomain) . ",42 " . ($domainFirst + ($lengthDomain / 2)) . ",57' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Ellipse:
            case 9:
                $forme = "<ellipse cx='" . ($domainFirst + ($lengthDomain / 2)) . "' cy='42' rx='" . ($lengthDomain / 2) . "' ry='15' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></ellipse>\n";
                break;

            default:
        }
        return $forme;
    }


    private static function choice_compact($randomForm, $domainFirst, $lengthDomain, $domainId, $domainConfiance,$miseAEchelle,$svgWidth,$length)
    {
        
        $domainStart=$domainFirst;
        $realLength = $lengthDomain;
        if ($miseAEchelle) {
            $domainFirst = ($domainFirst * $svgWidth) / $length;
            $lengthDomain = ($lengthDomain * $svgWidth) / $length;
        } 
        // alpha est le facteur de correction de la taille du domaine, ainsi si un domaine est trop petit pour être affiché avec une certaine forme
        // il est élargi d'un facteur alpha correspondant à la largeur necessaire (20 ici).
        if (($lengthDomain) < 20) {
            $alpha = (20 - $lengthDomain) / 2;
        } else {
            $alpha = 0;
        }
        switch ($randomForm) {
                //rectangle sans arrondi:
            case 0:
                $forme = "<rect x='" . $domainFirst
                    . "' y='5' width='" . $lengthDomain
                    . "' height='30'  fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></rect>\n";

                break;
                //rectangle avec arrondi:
            case 1:
                //après avoir défini notre linear gradient on fait appel à lui dans la définition de notre rectangle par le biais de fill =fill="url(#MyGradient'.$domain->getId().')"
                $forme = "<rect x='" . $domainFirst
                    . "' y='5' width='" . $lengthDomain
                    . "' height='30'  rx='10' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></rect>\n";

                break;
                //Hexagone
            case 2:
                $forme = "<polygon points='" . (($domainFirst + 10) - $alpha) . ",5 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",5 " . (($domainFirst + $lengthDomain) + $alpha) . ",25 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",34 " . (($domainFirst + 10) - $alpha) . ",34 " . (($domainFirst) - $alpha) . ",25' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Rectangle aux bords droit et gauche en courbe vers l'intérieur :
            case 3:
                $forme = "<path d='M" . ($domainFirst - $alpha) . " 5 h " . ($lengthDomain + $alpha) . " q-15,15 0,30 h -" . ($lengthDomain + $alpha) . " q15,-15 0,-30 'fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></path>\n";
                break;
                //Noeud papillon:
            case 4:
                $forme = "<polygon points='" . $domainFirst . ",5 " . ($domainFirst + $lengthDomain / 2) . ",19 " . ($domainFirst + $lengthDomain) . ",5 " . ($domainFirst + $lengthDomain) . ",34 " . ($domainFirst + $lengthDomain / 2) . ",31 " . $domainFirst . ",34' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Enclume inversée :
            case 5:
                $forme = "<polygon points='" . (($domainFirst + 10) - $alpha) . ",5 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",5 " . (($domainFirst + $lengthDomain) + $alpha) . ",34 " . ($domainFirst - $alpha) . ",34' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Enclume :
            case 6:
                $forme = "<polygon points='" . ($domainFirst - $alpha) . ",5 " . (($domainFirst + $lengthDomain) + $alpha) . ",5 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",34 " . (($domainFirst + 10) - $alpha) . ",34' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Flêche :
            case 7:
                $forme = "<polygon points='" . ($domainFirst - $alpha) . ",5 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",5 " . (($domainFirst + $lengthDomain) + $alpha) . ",25 " . ((($domainFirst + $lengthDomain) - 10) + $alpha) . ",34 " . ($domainFirst - $alpha) . ",34 " . (($domainFirst + 10) - $alpha) . ",25' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Losange:
            case 8:
                $forme = "<polygon points='" . $domainFirst . ",25 " . ($domainFirst + ($lengthDomain / 2)) . ",5 " . ($domainFirst + $lengthDomain) . ",25 " . ($domainFirst + ($lengthDomain / 2)) . ",34' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></polygon>\n";
                break;
                //Ellipse:
            case 9:
                $forme = "<ellipse cx='" . ($domainFirst + ($lengthDomain / 2)) . "' cy='25' rx='" . ($lengthDomain / 2) . "' ry='9' fill='url(#MyGradient" . $domainId . ")' stroke='black' stroke-width='2'><title> Name : " . $domainId . " \n Length ". $realLength . " \n Start : ". $domainStart . " \n End : " . ($realLength+ $domainStart) . " \n Trust : " . $domainConfiance
                    . "</title></ellipse>\n";
                break;

            default:
        }
        return $forme;
    }
}
