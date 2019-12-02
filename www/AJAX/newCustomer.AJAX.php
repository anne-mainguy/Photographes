<?php

include ('../../MODEL/addNewCustomers.php');
include ('../../CONTROLLER/verifInputForChangeUser.php');


if(isset($_SESSION['admin']) and !empty($_SESSION['admin'])){ 
    $errors = [];
    $errorsFiles = [];

    
    if(isset($_POST['identifiant']) and !empty($_POST['identifiant'])){
        $username = Username($_POST['identifiant']);
        if($username != false){
            $nameFolder = createNameFolder($username);
            $pathFolder = "../www/img/forCustomers/" . $nameFolder;
            mkdir(preg_replace('#\/www#', "" , $pathFolder));
        }
        else {
            $errors['identifiant'] = "Identifiant incorrect !";
        }
    }
    else {
        $errors['identifiant'] = "Vous devez indiquer le nom.";
    }


    if(isset($_POST['firstName1']) and !empty($_POST['firstName1'])){
        $firstName1 = controlFirstName($_POST['firstName1']);

        if($firstName1 == false){
            $errors['firstName1'] = "Saisie incorrect !";  
        }
    }
    else {
        $errors['firstName1'] = "Vous devez indiquer le prénom 1.";
    }


    if(isset($_POST['firstName2']) and !empty($_POST['firstName2'])){
        $firstName2 = controlFirstName($_POST['firstName2']);
        
        if($firstName2 == false){
            $errors['firstName2'] = "Saisie incorrect !";  
        }
    }
    else {
        $errors['firstName2'] = "Vous devez indiquer le prénom 2.";
    }


    if(isset($_POST['password']) and !empty($_POST['password'])){
        $password = controlPassword($_POST['password']);
        if($password == 'error length'){
            $errors['password'] = "Entre 8 et 12 caractères.";
        }
        else if($password == false){
            $errors['password'] = "Uniquement des chiffres et des lettres";
        }
    }
    else {
        $errors['password'] = "Vous devez spécifier un mot de passe.";
    }


    if(isset($_POST['email']) and !empty($_POST['email'])){
        $email = controlEmail($_POST['email']);
        if($email == false){
            $errors['email'] = "Saisie de l'email incorrect !";
        }
    }
    else {
        $errors['email'] = "Email obligatoire";
    }
    

    if(isset($_POST['phone']) and !empty($_POST['phone'])){
        $phone = controlPhone($_POST['phone']);
        if($phone == false){
            $errors['phone'] = "Saisie du telephone incorrect !";
        }
    }
    else {
        $phone = NULL;
    }


    if(isset($_POST['weddingDate']) and !empty($_POST['weddingDate'])){
        $weddingDate = controlWeddingDate($_POST['weddingDate']);
        if($weddingDate == false){
            $errors['weddingDate'] = "Saisie date de mariage incorrect !";
        }
    }
    else {
        $errors['weddingDate'] = "Vous devez préciser la date du mariage.";
    }


    if(isset($_POST['link']) and !empty($_POST['link'])){
        $link = controlLink($_POST['link']);
        if($link == false){
            $errors['link'] = "Lien dropbox incorrect !";
        }
    }
    else {
        $errors['link'] = "Vous devez fournir un lien dropbox.";
    }


    if(isset($_FILES['imageCouv']['tmp_name']) and !empty($_FILES['imageCouv']['tmp_name'])){
        if(isset($pathFolder) and file_exists(preg_replace('#\/www#', '', $pathFolder))){
            if(is_uploaded_file($_FILES['imageCouv']['tmp_name'])){
                $pathImageCouv = controlFile($_FILES['imageCouv'], 'imageCouv', $pathFolder );
                if($pathImageCouv == false){
                    $errorsFiles['imageCouv'] = "Fichier incorrect";
                }
            }
            else {
                $errorsFiles['imageCouv'] = "Probleme au téléchargement";
            }
        }
    }
    else {
        $errorsFiles['imageCouv'] = "Aucun fichier sélectionné";
    }


    if(isset($_FILES['facture']['tmp_name']) and !empty($_FILES['facture']['tmp_name'])){
        if(isset($pathFolder) and file_exists(preg_replace('#\/www#', '', $pathFolder))){
            if(is_uploaded_file($_FILES['facture']['tmp_name'])){
                $pathFacture = controlFile($_FILES['facture'],'facture', $pathFolder );
                if($pathFacture == false){
                    $errorsFiles['facture'] = "Fichier incorrect";
                }
            }
            else {
                $errorsFiles['facture'] = "Probleme au telechargement";
            }
        }
    }
    else {
        $errorsFiles['facture'] = "Aucun fichier sélectionné";
    }


    if(isset($_POST['noteAdmin']) and !empty($_POST['noteAdmin'])){
        $noteAdmin = controlNoteAdmin($_POST['noteAdmin']);
    }
    else {
        $noteAdmin = NULL;
    }


    if(empty($errors) and empty($errorsFiles)){
        addCustomer($username, $firstName1, $firstName2, $password, $email, $phone = NULL, $weddingDate, $link , $pathFolder, $pathImageCouv, $pathFacture , $noteAdmin = 'Absent' );
        echo (json_encode(['success' => 'tout est ok']));
    }
    else {
        if(isset($pathFolder) and file_exists(preg_replace('#\/www#', '', $pathFolder))){
            emptyAndDeleteFolder(preg_replace('#\/www#' , '' , $pathFolder));
        }
        echo (json_encode(['errors' => $errors, 'errorsFiles' => $errorsFiles]));
    }





}