<?php
#ini_set('max_execution_time', 300); //300 seconds = 5 minutes
require_once('Tools.php');


class Statistics
{

    private $clusters; # Array of Protein's Array

    private $nbGroups; # Matrice de Distances entre individus (protéines)
    private $nbSequences;

    private $domainPerProtein;

    private $protDomains;

    private $proteins;

    public function __construct($clusters)
    {
        $this->clusters = $clusters;
        $this->nbGroups = count($clusters);
        $nbSequences = 0;
        $nbDomains = 0;
        $protDomains = [];

        $prots = array();
        foreach ($clusters as $group) {
            foreach ($group as $protein) {
                $prots[] = $protein;
                $nbSequences++;
                foreach ($protein->getDomains() as $domain) {
                    $nbDomains++;
                    if (!isset($protDomains[$domain->getId()])) {
                        $protDomains[$domain->getId()]['nb'] = 0;
                    }
                    $protDomains[$domain->getID()]['nb']++;
                }
            }
        }
        //   arsort($protDomains);
        //$protDomains=array_slice($protDomains,0,20);
        $this->nbSequences = $nbSequences;
        $this->domainPerProtein = round($nbDomains / $nbSequences);
        $this->protDomains = $protDomains;
        /*     echo '<pre>';
        print_r($prots);
        echo '</pre>';
    */
        $this->proteins = $prots;
    }

    public function getNbGroups()
    {
        return $this->nbGroups;
    }

    public function getNbSequences()
    {
        return $this->nbSequences;
    }

    public function getDomainPerProtein()
    {
        return $this->domainPerProtein;
    }

    public function doHistogram()
    {
        $histo = "price\n";
        foreach ($this->protDomains as $prot) {
            $histo .= $prot['nb'] . ".0\n";
        }
        file_put_contents('One2.csv', trim($histo));

        echo '<!-- Create a div where the graph will take place -->
        <div id="histogram"></div>
        
        <script>
        // set the dimensions and margins of the graph
        var margin = {top: 10, right: 30, bottom: 30, left: 40},
            width = 460 - margin.left - margin.right,
            height = 400 - margin.top - margin.bottom;
        
        // append the svg object to the body of the page
        var svg = d3.select("#histogram")
          .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
          .append("g")
            .attr("transform",
                  "translate(" + margin.left + "," + margin.top + ")");
        
        // get the data
        d3.csv("One2.csv", function(data) {
        
          // X axis: scale and draw:
          var x = d3.scaleLinear()
              .domain([0, d3.max(data, function(d) { return +d.price })])     // can use this instead of 1000 to have the max of data: d3.max(data, function(d) { return +d.price })
              .range([0, width]);
          svg.append("g")
              .attr("transform", "translate(0," + height + ")")
              .call(d3.axisBottom(x));
        
          // set the parameters for the histogram
          var histogram = d3.histogram()
              .value(function(d) { return d.price; })   // I need to give the vector of value
              .domain(x.domain())  // then the domain of the graphic
              .thresholds(x.ticks(20)); // then the numbers of bins
        
          // And apply this function to data to get the bins
          var bins = histogram(data);
        
          // Y axis: scale and draw:
          var y = d3.scaleLinear()
              .range([height, 0]);
              y.domain([0, d3.max(bins, function(d) { return d.length; })]);   // d3.hist has to be called before the Y axis obviously
          svg.append("g")
              .call(d3.axisLeft(y));
        
          // Add a tooltip div. Here I define the general feature of the tooltip: stuff that do not depend on the data point.
          // Its opacity is set to 0: we don\'t see it by default.
          var tooltip = d3.select("#histogram")
            .append("div")
            .style("opacity", 0)
            .attr("class", "tooltip")
            .style("background-color", "black")
            .style("color", "white")
            .style("border-radius", "5px")
            .style("padding", "10px")
        
          // A function that change this tooltip when the user hover a point.
          // Its opacity is set to 1: we can now see it. Plus it set the text and position of tooltip depending on the datapoint (d)
          var showTooltip = function(d) {
            tooltip
              .transition()
              .duration(100)
              .style("opacity", 1)
            tooltip
              .html("Range: " + d.x0 + " - " + d.x1)
              .style("left", (d3.mouse(this)[0]+20) + "px")
              .style("top", (d3.mouse(this)[1]) + "px")
          }
          var moveTooltip = function(d) {
            tooltip
            .style("left", (d3.mouse(this)[0]+20) + "px")
            .style("top", (d3.mouse(this)[1]) + "px")
          }
          // A function that change this tooltip when the leaves a point: just need to set opacity to 0 again
          var hideTooltip = function(d) {
            tooltip
              .transition()
              .duration(100)
              .style("opacity", 0)
          }
        
          // append the bar rectangles to the svg element
          svg.selectAll("rect")
              .data(bins)
              .enter()
              .append("rect")
                .attr("x", 1)
                .attr("transform", function(d) { return "translate(" + x(d.x0) + "," + y(d.length) + ")"; })
                .attr("width", function(d) { return x(d.x1) - x(d.x0) -1 ; })
                .attr("height", function(d) { return height - y(d.length); })
                .style("fill", "#69b3a2")
                // Show tooltip on hover
                .on("mouseover", showTooltip )
                .on("mousemove", moveTooltip )
                .on("mouseleave", hideTooltip )
        
        });
        delete(x);
        delete(y);
        </script>';
        //  return $this->Histogram;
    }

    public function doTop20Domains()
    {
        $myFile = "pfam27DomainName.txt";
        preg_match_all('/(.*)\t(.*)/', trim(file_get_contents($myFile)), $items);
        $quest = array_combine($items[1], $items[2]);


        $top20 = $this->protDomains;
        arsort($top20);
        $top20 = array_slice($top20, 0, 20);
        $top = "Country,Value\n";
        foreach ($top20 as $id => $size) {

            $top .= $quest[$id] . ", " . $size['nb'] . "\n";
        }
        file_put_contents('One3.csv', trim($top));

        echo '<div id="top20"></div>';
        echo '<script>


        // set the dimensions and margins of the graph
        var marginTop20 = {top: 20, right: 30, bottom: 40, left: 90},
            width = 460 - marginTop20.left - marginTop20.right,
            height = 400 - marginTop20.top - marginTop20.bottom;
        
        // append the svg object to the body of the page
        var svgTop20 = d3.select("#top20")
          .append("svg")
            .attr("width", width + marginTop20.left + marginTop20.right)
            .attr("height", height + marginTop20.top + marginTop20.bottom)
          .append("g")
            .attr("transform",
                  "translate(" + marginTop20.left + "," + marginTop20.top + ")");
        
        // Parse the Data
        d3.csv("One3.csv", function(data) {
        
          // Add X axis
          var x = d3.scaleLinear()
            .domain([0, ' . array_values($top20)[0]['nb'] . '])
            .range([ 0, width]);
          svgTop20.append("g")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x))
            .selectAll("text")
              .attr("transform", "translate(-10,0)rotate(-45)")
              .style("text-anchor", "end");
        
          // Y axis
          var y = d3.scaleBand()
            .range([ 0, height ])
            .domain(data.map(function(d) { return d.Country; }))
            .padding(.1);
          svgTop20.append("g")
            .call(d3.axisLeft(y))
        
          //Bars
          svgTop20.selectAll("myRect")
            .data(data)
            .enter()
            .append("rect")
            .attr("x", x(0) )
            .attr("y", function(d) { return y(d.Country); })
            .attr("width", function(d) { return x(d.Value); })
            .attr("height", y.bandwidth() )
            .attr("fill", "#69b3a2")
        
        
            // .attr("x", function(d) { return x(d.Country); })
            // .attr("y", function(d) { return y(d.Value); })
            // .attr("width", x.bandwidth())
            // .attr("height", function(d) { return height - y(d.Value); })
            // .attr("fill", "#69b3a2")
        
        })
        
        
        </script>';
    }




    public function doTop20Archs($domainProperties)
    {

        $myFile = "pfam27DomainName.txt";
        preg_match_all('/(.*)\t(.*)/', trim(file_get_contents($myFile)), $items);
        $quest = array_combine($items[1], $items[2]);

        $top20archs = array();
        foreach ($this->proteins as $prot) {
            $archs = '';
            $domains = array();
            foreach ($prot->getDomains() as $domain) {
                $archs .= $domain->getID();
                $domains[] = $domain;
            }
            if (!isset($top20archs[$archs])) {
                $top20archs[$archs]['nb'] = 0;
                $top20archs[$archs]['domains'] = array();
            }
            $top20archs[$archs]['nb']++;
            $top20archs[$archs]['domains'] = $domains;
        }
        arsort($top20archs);
        $top20archs = array_slice($top20archs, 0, 20);
       
        afficher_top20_archs($top20archs, $domainProperties);
    }
}
