<?php

include 'dataBase.php' ;

function getUser($pseudo){
    global $pdo;
    
    $req = $pdo->prepare('
    SELECT Password, ID   
    FROM customers 
    WHERE Identifying = :pseudo' 
    );
    
    $req->execute(array(
        ':pseudo' => $pseudo
    ));
    
    $result = $req->fetch(PDO::FETCH_ASSOC);

    return $result;
    
}

function getDataUser($id){
    global $pdo;
    
    $req = $pdo->prepare('
    SELECT * 
    FROM customers 
    WHERE ID = :ID' 
    );
    
    $req->execute(array(
        ':ID' => $id
    ));
    
    $result = $req->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}

function getAdmin($pseudo, $password){
    global $pdo;
    
    $req = $pdo->prepare('
        SELECT *
        FROM iajae_admin
        WHERE iajae_Pseudo = :pseudo AND iajae_Password = :password
    ');
    
    $req->execute(array(
        ':pseudo' => $pseudo,
        ':password' => $password
    ));
    
    $result = $req->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}
