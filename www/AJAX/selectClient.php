<?php
include '../../MODEL/requetes.php';
           

if(isset($_POST['idUser']) and !empty($_POST['idUser'])){
    $infosClient = getUserById($_POST['idUser']);
    if($infosClient != 0){
        echo(json_encode($infosClient));
    }
    
}