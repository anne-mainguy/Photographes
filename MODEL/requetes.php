<?php

include_once 'dataBase.php' ;



function getLatestUsers(){
    global $pdo;
    
    $req = $pdo->query('
    SELECT * , DATE_FORMAT(WeddingDate, "%d / %m / %Y") AS WeddingDateFR
    FROM customers
    ORDER BY WeddingDate DESC LIMIT 3 
    ');
    
    $results = $req->fetchAll();
    
    return $results;
}



function getAllIdUsersDate(){
    global $pdo;
    
    $req = $pdo->query('
    SELECT ID , DATE_FORMAT(WeddingDate, "%Y/%m/%d") AS WeddingDateFR
    FROM customers
    ORDER BY WeddingDate DESC
    ');
    
    $listUsers = $req->fetchAll();
    
    return $listUsers;
}

function getAllIdUsersIdentifiant(){
    global $pdo;
    
    $req = $pdo->query('
    SELECT ID , Identifying
    FROM customers
    ORDER BY Identifying
    ');
    
    $listUsers = $req->fetchAll();
    
    return $listUsers;
}


function deleteUserById($id){
    global $pdo;
    $req = $pdo->prepare('
    DELETE FROM customers 
    WHERE ID = :id
    ');
    $req->execute(array(
    ':id' => $id
    ));
    
    
    return $req->rowCount();
}


function getUserById($id){
    global $pdo;
    $req = $pdo->prepare('
    SELECT *
    FROM customers
    WHERE ID = :id
    ');
    
     $req->execute(array(
        ':id' => $id
    ));
    
    $result = $req->fetch(PDO::FETCH_ASSOC);

    return $result;
}


function updateUserById(array $tabElt, $idCustomer){
    $fields = "";
    foreach($tabElt as $key => $val){
        $fields .= " " . $key . " = :" . $key . ",";
    }

    $fields = preg_replace('/[,]$/', '' , $fields);
    $values = [];
    foreach($tabElt as $key => $val){
        $values[":$key"] = $val;
    }
    $values[":idCustomer"] = $idCustomer;

    global $pdo;
    $req = $pdo->prepare(
        'UPDATE customers
        SET ' . $fields .        
        ' WHERE 
        ID = :idCustomer'
    );
    $req->execute($values);

    return $req->rowCount();
}