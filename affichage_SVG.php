<?php

require "fonctions.php";

$title="hello";

$body =  "<body>\n$title" . 
        "</body></html>\n";
echo debut_html($title) . $body;


?>