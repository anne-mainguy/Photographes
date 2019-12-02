<?php
session_start();


if(isset($_SESSION['user']) AND !empty($_SESSION['user'])){
    $template = "VIEW/monEspace";
    $css =  "monEspace";
    include '../layout.phtml';
}
elseif(isset($_SESSION['admin']) AND !empty($_SESSION['admin'])){
    header('location: gestionAdmin.php');
}

