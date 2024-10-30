<?php

require 'vendor/autoload.php';

use Phpml\Regression\LeastSquares;
use Phpml\Preprocessing\Normalizer;

// Jeu de données - chaque sous-tableau est une propriété
$samples = [
    [110, 3, 2.5, 10, 1],
    [75, 2, 5.0, 20, 0],
    [180, 5, 1.0, 5, 1],
    [55, 1, 6.0, 25, 0],
    [85, 2, 4.0, 15, 1],
    [140, 4, 3.0, 8, 1],
    [60, 2, 5.5, 20, 0],
];

// Labels - prix correspondant en euros
$labels = [280000, 170000, 400000, 110000, 200000, 320000, 120000];

// Normaliser les données pour améliorer les performances
$normalizer = new Normalizer();
$normalizer->fit($samples);
$normalizer->transform($samples);

// Créer le modèle de régression
$regression = new LeastSquares();
$regression->train($samples, $labels);

// Exemple de prédiction : 
// un bien avec 100m², 3 chambres, 2.0km du centre, 13 ans d'âge, commodités proches
$newProperty = [
  [100, 3, 2.0, 13, 1],
];
$normalizer->transform($newProperty); // Normalisation
$predictedPrice = $regression->predict($newProperty);

echo "Estimation du prix : " . round($predictedPrice[0], 2) . " €\n";
