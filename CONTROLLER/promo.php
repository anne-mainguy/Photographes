<?php

$saisiePromo = htmlspecialchars(strtolower($_POST['codePromo']));
$promo = 0;

switch($saisiePromo){
    case 'friend':
        $promo = 10;
        break;
    case 'cordialement':
        $promo = 23;
        break;
    case 'champion':
        $promo = 30;
        break;
    
    default :
        $promo = 0;
}

echo $promo;

