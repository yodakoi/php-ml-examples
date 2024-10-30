<?php

require 'vendor/autoload.php';

use Phpml\Classification\SVC;
use Phpml\Dataset\ArrayDataset;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Preprocessing\Normalizer;

// Fonction pour charger et redimensionner l'image en un tableau de valeurs RGB
function imageToFeatures(string $imagePath, int $size = 50) {
    $img = imagecreatefromjpeg($imagePath);
    // Redimensionner à 50x50 pixels
    $img = imagescale($img, $size, $size);
    $features = [];

    for ($y = 0; $y < $size; $y++) {
        for ($x = 0; $x < $size; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $features[] = $r; // Ajouter la valeur R
            $features[] = $g; // Ajouter la valeur G
            $features[] = $b; // Ajouter la valeur B
        }
    }
    return $features;
}

// Charger les données
$samples = [];
$labels = [];

// Charger les images de chats
foreach (glob('classification/data/cats/*.jpg') as $file) {
    $samples[] = imageToFeatures($file);
    $labels[] = 'chat';
}

// Charger les images de chiens
foreach (glob('classification/data/dogs/*.jpg') as $file) {
    $samples[] = imageToFeatures($file);
    $labels[] = 'chien';
}

// Normalisation des données
$normalizer = new Normalizer();
$normalizer->fit($samples);
$normalizer->transform($samples);

// Créer et entraîner le modèle SVM
$classifier = new SVC(Kernel::LINEAR, $cost = 1000);
$classifier->train($samples, $labels);

// Tester le modèle sur une nouvelle image
$monchat = imageToFeatures('classification/data/test/chat.jpg');
$chat = [$monchat];
// Normalisation
$normalizer->transform($chat);
$predictedLabel = $classifier->predict($chat);
echo "L'image 1 est un : " . $predictedLabel[0] . "\n";

$monChien = imageToFeatures('classification/data/test/chien.jpg');
$chien = [$monChien];
// Normalisation
$normalizer->transform($chien);
$predictedLabel = $classifier->predict($chien);
echo "L'image 2 est un : " . $predictedLabel[0] . "\n";
