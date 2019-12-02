<?php
session_start();
$template;
$page = $_GET['page'];

switch($page){
        
    case 'index';
        $template = 'VIEW/index';
        break;
    case 'faq':
        $template = 'VIEW/faq';
        break;
    case 'devis':
        $template = 'VIEW/devis';
        break;
    case 'contact':
        $template = 'VIEW/contact';
        break;
    case 'monEspace':
        $template = 'VIEW/monEspace';
        break;
        
        default ;
        $template = 'VIEW/ERREUR';
        break;
}

include '../layout.phtml';