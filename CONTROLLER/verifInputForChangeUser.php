<?php


include '../../MODEL/requetes.php';

date_default_timezone_set('Europe/Paris');


if(isset($_SESSION['admin']) and !empty($_SESSION['admin'])){
    
    $errors = array();
    $errorsCreate = array();
    

    function Username($username){
        $username = htmlspecialchars($username);
        $testUsername = preg_match('/^(([A-Z])?[a-zàéèêëîïôöûüùç]+)+([ -]([A-Z])?([a-zàéèêëîïôöûüùç])*)?$/', $username);
        
        if($testUsername){
            if(strlen($username) >= 2 && strlen($username) <= 25){
                return ucwords($username);
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }   
    }
    

    function createNameFolder($username){

        $usernameLower = htmlspecialchars((mb_strtolower($username)));
        $translit = array('á'=>'a','à'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y');
        $usernameNotAccents = strtr($usernameLower, $translit);
        $usernameForFolder = preg_replace('/[^a-z]/', '_' , $usernameNotAccents);

        $nameFolder = $usernameForFolder . '_' . date("d-m-Y_H-i-s");
        return $nameFolder;
    }
  

    function controlFirstName($firstName){
        $firstName = htmlspecialchars((mb_strtolower($firstName)));
        $firstName = ucfirst($firstName);

        if(strlen($firstName) >= 2 && strlen($firstName) <= 25 && preg_match('/^[a-zA-Zéèêëâäïüöôç]*[-]?[a-zA-Zéèêëâäïüöôç]*$/i', $firstName)){
            return $firstName;
        }
        else{
            return false;
        }
    }
    
    function checkFirstName($postFirstName, $bddFirstName){
        if(isset($postFirstName) and !empty($postFirstName)){
            $postFirstName = controlFirstName($postFirstName);
            if($postFirstName){
                if($postFirstName != $bddFirstName){
                    return $postFirstName;
                }
            }
            else{
                return 'invalid';
            }
        }
        else {
            return false;
        }
    }


    function controlPassword($password){
        if(!preg_match('/[^a-z0-9]/i', $password)){
            $nbPass = strlen($password);
            if(strlen($password) >= 8 && strlen($password) <= 12){
                $password = htmlspecialchars($password);
                $password = password_hash($password, PASSWORD_DEFAULT);
                return $password ;
            }
            else{
                return 'error length';
            }
        }
        else {
           return false;
        }
       
    }
   

    function controlEmail($email){
        $email = strtolower($email);
        $email = str_replace(' ', '', $email);
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email = htmlspecialchars($email);
            return $email;
        }
        else{
            return false;
        }
    } 
    

    function controlPhone($phone){
        $phoneProvisional = str_replace(" ", "", $phone);
        $phoneProvisional = str_replace("-", "", $phoneProvisional);
        $phoneProvisional = str_replace("_", "", $phoneProvisional);
        $phoneProvisional = str_replace("/", "", $phoneProvisional);
        $phoneProvisional = str_replace(".", "", $phoneProvisional);

        if(!ctype_digit($phoneProvisional)){
            return false;
        }
        elseif(strlen($phoneProvisional) != 10){
            return false;
        }
        else{
            $phone = $phoneProvisional;
            return $phone;
        }
    }


    function controlWeddingDate($weddingDate){
        $regex = '#^20([0-9]{2}[/|-])(?![0]{2})(([0-1]{1})([0-2]{1})|([0]{1})([0-9]{1}))[/|-](?![0]{2})(([0-3]{1}[0-1]{1})|([0-2]{1}[0-9]{1}))(?!.)#';
        if(preg_match($regex, $weddingDate)){
            return $weddingDate;
        }
        else {
            return false;
        }
    }


    function controlLink($link){
        $link = htmlspecialchars($link);
        $link = str_replace(" ", "", $link);
        $reg = '#^https:\/\/www.dropbox.com\/[a-zA-Z0-9-/]+(\?dl=0)$#';
        if(preg_match($reg, $link)){
            return $link;
        }
        else {
            return false;
        }
    }
    

    function createNameFile($kindFile){
        $nameFile = $kindFile . '_' . date("d-m-Y_H-i-s");
        return $nameFile;
    }
    

    function controlFile(array $file, $nameFile, $pathFolder ){
        if(!empty($file['tmp_name']) AND is_uploaded_file($file['tmp_name'])){

            if($nameFile == 'facture'){
                $type_mime_accept = ["application/pdf" => '.pdf' ];
                $max_size = 1048576;//1Mo
            }
            else if($nameFile == 'imageCouv'){
                $type_mime_accept = ["image/jpg" => '.jpg', "image/jpeg" => '.jpeg', "image/gif" => '.gif', "image/png" => '.png' ];
                $max_size = 524288;//0.5Mo soit 500ko
            }

            $type_MIME = mime_content_type($file['tmp_name']);
            $elementsName = explode('.', $file['name']);

            if(count($elementsName) < 3 AND filesize($file['tmp_name']) < $max_size AND array_key_exists($type_MIME, $type_mime_accept)){
                $extentionDef = $type_mime_accept[$type_MIME];
                $nameFile = createNameFile($nameFile);
                $pathImageForBdd = $pathFolder . '/' . $nameFile . $extentionDef;
                $pathImageForUpload = preg_replace('#\/www#', '', $pathImageForBdd);
                if(move_uploaded_file( $file['tmp_name'], $pathImageForUpload )){
                    return $pathImageForBdd;
                }
                return false;                
            }else {
                return false;
            }
        }
    }


    function controlFilesReplacing(array $infosUser, $pathFolderBackupTemporary, $nameFile, array $file, $nameChampDb){

        if(is_uploaded_file($file['tmp_name'])){
            
            $validationUpload = controlFile($file , $nameFile, $infosUser['PathFolder']);

            if($validationUpload != false){
                $newFilePathForDb = $validationUpload;
                
                $infosOldPathFolder = pathinfo($infosUser[$nameChampDb]);
                $pathForDb = $infosOldPathFolder['dirname'];
                $nameOldFile = $infosOldPathFolder['basename'];
                $pathFolderOldFile = preg_replace('#\/www#', '', $infosUser[$nameChampDb]);

                $copyOldFile = rename($pathFolderOldFile, $pathFolderBackupTemporary . $nameOldFile);
                if(!file_exists(preg_replace('#\/www#', '', $newFilePathForDb)) || !$copyOldFile){
                    return "Error upload";
                }
                else {
                    return $newFilePathForDb;
                }
            }
            else {
                return "Error file";
            }
        }
        else {
            return "Erreur dans le téléchargement '" . $nameFile . "'";
        } 
    }
    

    function toggleOldFilesFromFolderTemp($pathFolderBackupTemporary, $pathFolderOriginal){
        if($tempFolder = opendir($pathFolderBackupTemporary)){
            while(($fileTemp = readdir($tempFolder)))
            {
                if(is_file($pathFolderBackupTemporary. '/' . $fileTemp)) {
                    rename($pathFolderBackupTemporary. '/' . $fileTemp, $pathFolderOriginal. '/' . $fileTemp) ;
                }
            }
        }
        rmdir($pathFolderBackupTemporary);
    }


    function emptyAndDeleteFolder($pathFolder){
        if($tempFolder = opendir($pathFolder))
        {
            while(($file = readdir($tempFolder)))
            {
                if(is_file($pathFolder . '/' . $file)){
                    unlink($pathFolder . '/' . $file);
                }
            }
        }
        rmdir($pathFolder);
    }


    function deleteNewFilesFromFolderUser($infosUser, $pathFolderOriginal){
        $nameFileFactureOriginal = basename($infosUser["Facture"]);
        $nameFileImageCouvOriginal = basename($infosUser["ImageCouv"]);

        if($originalFolder = opendir($pathFolderOriginal))
        {
            while(($file = readdir($originalFolder)))
            {
                if(is_file($pathFolderOriginal . '/' . $file) && $file != $nameFileFactureOriginal && $file != $nameFileImageCouvOriginal){
                    unlink($pathFolderOriginal . '/' . $file);
                }
            }
        }
    }


    function controlNoteAdmin($noteAdmin){
        $noteAdmin = htmlspecialchars($noteAdmin);
        return $noteAdmin;
    }
     
}



