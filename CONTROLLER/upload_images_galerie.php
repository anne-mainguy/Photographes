<?php
session_start();

if(isset($_SESSION['admin']) AND !empty($_SESSION['admin']))
{
    $nbFiles = count($_FILES['upload_files']['name']);

    for($i = 0; $i <= 4 ; $i++)
    {
        if(isset($_FILES['upload_files']['name'][$i]) AND !empty($_FILES['upload_files']['name'][$i]))
        {
            $maxsize = 524288;//0.5Mo soit 500ko 
            $extentions_valides = array('jpg', 'jpeg', 'png');
            $extention = strrchr($_FILES['upload_files']['name'][$i], '.');
            date_default_timezone_set('Europe/Paris');
            $place_definitive = "../www/img/for_gallery/" . date("d-m-Y_H-i-s") . '_' . $i . $extention ;

            if($_FILES['upload_files']['name'][$i] )
            {
                if ($_FILES['upload_files']['size'][$i] < $maxsize )
                {
                    $extention_upload = strtolower( substr( strrchr($_FILES['upload_files']['name'][$i], '.') ,1 ) );
                    if (in_array($extention_upload, $extentions_valides))
                    {

                        move_uploaded_file($_FILES['upload_files']['tmp_name'][$i], $place_definitive );
                    }else
                    {
                        $erreur = "Fichier incorrect";
                    }
                }else
                {
                     $erreur = "le fichier est trop gros";
                } 
            }else
            {
                $erreur = "Erreur lors du transfets";
            }

        } 
    }
    echo "<script type='text/javascript'>document.location.replace('galerie.php#admin');</script>";

}
else
{
    echo "<script type='text/javascript'>document.location.replace('galerie.php#admin');</script>";
}

