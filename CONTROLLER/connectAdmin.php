<?php
include '../MODEL/login.php';

if(isset($_POST['username']) and !empty($_POST['username']) and isset($_POST['password']) and !empty($_POST['password'])){

    $identifiant = htmlspecialchars(strtoupper($_POST['username']));
    $password = htmlspecialchars(strtoupper($_POST['password']));

    $identite = getAdmin($identifiant, $password);

    if($identite){
        unset($_SESSION['user']);
        $_SESSION['admin'] = $identite;
        echo "success";
    }else{
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        echo "Failed";
    }
}