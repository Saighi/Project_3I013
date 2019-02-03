<?php

function debut_html($title) {
    return
      "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML Basic 1.1//EN'
          'http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd'>\n" .
      "<html xmlns='http://www.w3.org/1999/xhtml' lang='fr'>\n" .
      "<head>\n" .
      "<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />\n" .
      "<title>" . 
      $title .
      "</title>\n" .
      "</head>\n";
  }

$title="hello";

$body =  "<body>\n$title" . 
            "<form action='saisie_unique.php' method='post'><fieldset>\n" .
            "<label for id='nom'>Rentrez votre fichier ;) : </label>\n" .
            "<input type ='file' id='fichier' name='fichier' />\n" .
            "<input type ='submit' id='submit' name='fichier' />\n" .
            "</fieldset></form></body></html>\n";
echo debut_html($title) . $body;


?>
