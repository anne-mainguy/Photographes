<?php

if(isset($_POST['submit_sup'])){
    
    
    if(isset($_POST['selectImage']) and !empty($_POST['selectImage'])){
        foreach($_POST['selectImage'] as $image){
            unlink($image);
        }
    }
    
}

echo "<script type='text/javascript'>document.location.replace('galerie.php#admin');</script>";

