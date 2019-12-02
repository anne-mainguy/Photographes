<?php

include_once 'dataBase.php';

function addCustomer($identifiant, $firstName1, $firstName2, $password, $email, $phone = NULL, $weddingDate, $link , $pathFolder, $imageCouv, $facture , $noteAdmin = 'Absent' ){
    
    global $pdo;
    $req = $pdo->prepare(
        "INSERT INTO customers
        (Identifying,
        FirstName1,
        FirstName2,
        Password,
        Email,
        Phone,
        WeddingDate,
        Link,
        pathFolder,
        ImageCouv,
        Facture,
        NoteAdmin
        )
        VALUES
        (:identifiant, :firstName1, :firstName2, :password, :email, :phone,  :weddingDate, :linkDownload, :pathFolder,  :imageCouv, :facture ,:noteAdmin )"
    );
    
    $req->execute(array(
        ':identifiant' => $identifiant, 
        ':firstName1' => $firstName1, 
        ':firstName2' => $firstName2, 
        ':password' => $password, 
        ':email' => $email, 
        ':phone' => $phone, 
        ':weddingDate' => $weddingDate, 
        ':linkDownload' => $link, 
        ':pathFolder' => $pathFolder,
        ':imageCouv' => $imageCouv, 
        ':facture' => $facture,
        ':noteAdmin' => $noteAdmin
    ));
    
    return $req;
}
