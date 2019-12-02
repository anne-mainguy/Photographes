<?php
include '../MODEL/login.php';

if(isset($_POST['username']) and !empty($_POST['username']) and isset($_POST['password']) and !empty($_POST['password'])){
    
    $identifiant = htmlspecialchars(strtolower($_POST['username']));
    $password = htmlspecialchars(strtolower($_POST['password']));
    
    $validIdentite = getUser($identifiant);
    
    if(password_verify($password, $validIdentite['Password'])){   
        $identite = getDataUser($validIdentite['ID']);
        $date_time = new DateTime($identite['WeddingDate']);
        $intl_date_formatter = new IntlDateFormatter('fr_FR',
                                                    IntlDateFormatter::FULL,
                                                    IntlDateFormatter::NONE);
        $identite['WeddingDate'] = ucfirst($intl_date_formatter->format($date_time));
    }

    if($identite != false){
        unset($_SESSION['admin']);
        $_SESSION['user'] = $identite;
        echo "success";
    }else{
        unset($_SESSION['admin']);
        unset($_SESSION['user']);
        echo false;
    }
}

