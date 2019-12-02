<?php
include '../MODEL/requetes.php';

    if(isset($_SESSION['admin']) and !empty($_SESSION['admin'])){
 
        $users = getLatestUsers();      
        $allIdUsersIdentifiant = getAllIdUsersIdentifiant();      
        $allIdUsersDate = getAllIdUsersDate(); 
        
        $template = "VIEW/gestionComptes"; 
        include '../layout.phtml';
    }else {
        $template = 'VIEW/ERREUR';
        include '../layout.phtml';
    }

    