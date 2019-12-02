<?php

include '../../MODEL/requetes.php';



if(isset($_POST['idUser']) and !empty($_POST['idUser']) and isset($_SESSION['admin']) and !empty($_SESSION['admin'])){
         
    $idUser = (int)$_POST['idUser'];
        
        $infosUser = getUserById($idUser);
        if(!empty($infosUser)){
            $folderSupp = preg_replace('#\/www#', '', $infosUser['PathFolder']);
            if($folder = opendir($folderSupp))
            {
                while(($file = readdir($folder)))
                {
                    if(is_file($folderSupp. '/' . $file)){
                        unlink($folderSupp . '/' . $file);
                    }
                }
            }
            rmdir($folderSupp);
            
            if(!file_exists($folderSupp)){
                    $result = deleteUserById($idUser);
                    if($result == 1){
                        echo $result;
                    }
                    else{
                        echo "probleme de resultat de requete";
                    }
            }
            else{
                echo "le dossier n'est pas vide";
            } 
        }
        else{
            echo "probleme getUserById";
        }
    
}
else {
    echo '$post est vide';
}
                                    