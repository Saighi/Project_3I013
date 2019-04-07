<?php
include('vendor/autoload.php');
use NlpTools\Clustering\Hierarchical;
use NlpTools\Clustering\MergeStrategies\SingleLink;
use NlpTools\Clustering\KMeans;
use NlpTools\Similarity\Euclidean;
use NlpTools\Clustering\CentroidFactories\Euclidean as EuclideanCF;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\DataAsFeatures;


$tset = new TrainingSet();
$tset->addDocument(
    '', //class is not used so it can be empty
    new TokensDocument(array('x'=>0,'y'=>0))
);
$tset->addDocument(
    '', //class is not used so it can be empty
    new TokensDocument(array('x'=>1,'y'=>0))
);
$tset->addDocument(
    '', //class is not used so it can be empty
    new TokensDocument(array('x'=>0,'y'=>1))
);
$tset->addDocument(
    '', //class is not used so it can be empty
    new TokensDocument(array('x'=>3,'y'=>3))
);
$tset->addDocument(
    '', //class is not used so it can be empty
    new TokensDocument(array('x'=>2,'y'=>3))
);
$tset->addDocument(
    '', //class is not used so it can be empty
    new TokensDocument(array('x'=>3,'y'=>2))
);

$clust = new Hierarchical(
    new SingleLink(),
    new Euclidean()
);

// Assuming $tset is the same from the above example in K-Means
$dendrogram = $clust->cluster($tset, new DataAsFeatures());

print_r($dendrogram);
echo "</br>";
print_r(Hierarchical::dendrogramToClusters($dendrogram, 2));


