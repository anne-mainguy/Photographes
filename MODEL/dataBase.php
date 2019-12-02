<?php
session_start();
try{

    $pdo = new PDO ('mysql:host=localhost;dbname=********;charset=utf8', '********', '*********', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

}catch(Exeption $e){
    die('Erreur : ' . $e->getMessage());
}


$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

