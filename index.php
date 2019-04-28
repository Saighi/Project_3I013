<?php
#Tools.php : Recharger les Classes et les fonctions utiles
require_once('class/Tools.php');

/*Si données protéines à traiter (formulaire pas encore saisie)
    => Page Affichage de Protéines */
if (isset($_GET['file']) || isset($_FILES['fichier'])) {

    // Partie récupération des données et préférences utilisateur

    /* Soit l'utilisateur souhaite accèder au traitement du fichier qu'il vient de saisir
        Soit il souhaite accèder à un fichier déjà existant dans le serveur */

    if (isset($_FILES["fichier"])) { #Si il a saisi un fichier par le formulaire
        $name_file = upload_file($_FILES["fichier"]); #On le sauvegarde dans le serveur
    }

    #Déterminer les données si ils sont rentré par l'utilisateur par un fichier ou si le fichier exist déjà dans le serveur
    $fileProt = isset($_GET['file']) ? $_GET['file'] : $name_file;
    #Vérifier si le nombre de protéines par page est précisée par l'utilisateur (soit par le lien(get), soit par le formulaire(post))
    #Sinon initialiser à 20
    $nbProtPerPage = isset($_GET['nbProtPerPage']) ? $_GET['nbProtPerPage'] : (isset($_POST['nbProtPerPage']) ? htmlspecialchars($_POST['nbProtPerPage']) : 20);
    #Vérifier si la msie à échelle a été précisée (par GET ou POST) sinon initialiser à False
    $miseAEchelle = isset($_POST['miseAEchelle']) ? $_POST['miseAEchelle'] : (isset($_GET["miseAEchelle"]) ? $_GET["miseAEchelle"] : false);


    // Partie extraction données du fichier txt

    #On transforme les données du fichier en un objet ProteinsFromTxt
    $proteinsFromTxt = new ProteinsFromTxt(htmlspecialchars('data/proteines/' . $fileProt));
    #Nombre total des protéines
    $nbProt = count($proteinsFromTxt->getListOfProteins());
    /* Si le nombre total de protéines est inférieur au nombre de protéines par page
        on affiche toutes les protéines dans une page
    */
    $nbProtPerPage = $nbProt < $nbProtPerPage ? $nbProt : $nbProtPerPage;
    /* Nombre de Classes
        Soit une seule : Mode sans Clustering (arbre coupée à l'indice 0)
            nbClasses = 1
        Soit plusieurs : Mode Clustering
            nbClasses = nombre de protéines * indice de coupe
    */
    $nbClasses = ($_POST['cutTree'] == 0) ? 1 : round($nbProt * htmlspecialchars($_POST['cutTree']));
    $listOfProteins = $proteinsFromTxt->getListOfProteins();
    $domainProperties = $proteinsFromTxt->getDomainsProperties();


    // Corps de la page

    $title = "Protéines affichage";
    echo debut_html($title);
    echo "<body style='background-color:#F0F8FF'>\n";
    
    include('includes/navbar.html');
    
    #Créer l'objet Clusterer à partir de la liste de Protéines et d'un nombre de Classe déjà définie par l'indice de trie
    $clusterer = new Clusterer($listOfProteins, $nbClasses);
    #$clusters = un Tableau contenant des Groupes (tableaux) de Protéines.
    $clusters = $clusterer->getClusters();
    
    
    
    echo "<br /> <center>
    <iframe name='myframe' id='frame1' src='includes/ExtractExcel.php' style='width:0;height:0;border:0; border:none;'></iframe>

    <form method='POST' action='includes/ExtractExcel.php' target='myframe'>
    <input type='hidden' name='fileProt' value='" . $fileProt . "'/>
    <label for='name'> Nom de votre Excel </label>
    <br />
    <input type='text' style = 'width:25%;'  class='form-control' id='name' name='name' value='" . $fileProt . "' required/>
    <input type='hidden' name='nbClasses' value='" . $nbClasses . "'/>
    
    <button type='submit' style='width:30%;' class='btn btn-success btn-lg btn-block'>Extract to Excel</button>
    
    </form>
    </center>";

    echo "<br /> <br /> <center><div id='my_dataviz'></div></center>

    <script>

    // set the dimensions and margins of the graph
    var width = 460
    var height = 460

    // append the svg object to the body of the page
    var svg = d3.select('#my_dataviz')
    .append('svg')
        .attr('width', width)
        .attr('height', height)
    .append('g')
        .attr('transform', 'translate(40,0)');  // bit of margin on the left = 40

    // read json data
    d3.json('dendrogram.json', function(data) {

    // Create the cluster layout:
    var cluster = d3.cluster()
        .size([height, width - 100]);  // 100 is the margin I will have on the right side

    // Give the data to this cluster layout:
    var root = d3.hierarchy(data, function(d) {
        return d.children;
    });
    cluster(root);


    // Add the links between nodes:
    svg.selectAll('path')
        .data( root.descendants().slice(1) )
        .enter()
        .append('path')
        .attr('d', function(d) {
            return 'M' + d.y + ',' + d.x
                    + 'C' + (d.parent.y + 50) + ',' + d.x
                    + ' ' + (d.parent.y + 150) + ',' + d.parent.x // 50 and 150 are coordinates of inflexion, play with it to change links shape
                    + ' ' + d.parent.y + ',' + d.parent.x;
                })
        .style('fill', 'none')
        .attr('stroke', '#ccc')


    // Add a circle for each node.
    svg.selectAll('g')
        .data(root.descendants())
        .enter()
        .append('g')
        .attr('transform', function(d) {
            return 'translate(' + d.y + ',' + d.x + ')'
        })
        .append('circle')
            .attr('r', 7)
            .style('fill', '#69b3a2')
            .attr('stroke', 'black')
            .style('stroke-width', 2)

    })

</script>";

    afficher_clusters($clusters, $domainProperties, $miseAEchelle);
    
    #Fin page Affichage de protéines
    echo "</body></html>";
} else #Page Formulaire
    {

        $title = "Protéines Formulaire";

        echo debut_html($title);

        ?>

    <body style="background-color:#F0F8FF">
        <?php
        include('includes/navbar.html');
        ?>
        <br />
        <?php
        include('includes/formulaire.html');
        ?>
    
    </body>

    </html>
<?php
}
?>