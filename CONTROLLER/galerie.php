<?php
session_start();

$gallery = [];

$dossier = '../www/img/for_gallery/';
$open = opendir($dossier);

while($photo = readdir($open)){
    if(!is_dir($dossier.$photo) && $photo != '.' && $photo != '..'){
        array_push($gallery, $dossier.$photo);
    }
}

closedir($open);

function aleatoirDisplay($gallery){
    $num = 0;

    while(!empty($gallery)){

        $item = mt_rand(0 , count($gallery) - 1);
        $imageAleatoir = $gallery[$item];
        
        if(isset($_SESSION['admin']) and !empty($_SESSION['admin'])){
            echo  '<div class="containerImages">
                        <input type="checkbox" class="hide" name="selectImage[]" value="' . $imageAleatoir . '">
                        <a href="#" style="background-image : url('. $imageAleatoir .');" data-url="' . $imageAleatoir . '" data-item="' . $num .'"></a>
                    </div>';

            array_splice($gallery, $item, 1); 
            $num += 1;
                        
        }else {
            echo  '<div class="containerImages">
                        <a href="#" style="background-image : url('. $imageAleatoir .');" data-url="' . $imageAleatoir . '" data-item="' . $num .'"></a>
                    </div>';
            array_splice($gallery, $item, 1); 
            $num += 1;
        }
        
    }
    return false;
};

$template = 'VIEW/galerie';
$css = 'galerie';
include '../layout.phtml';
