<?php


include '../../CONTROLLER/verifInputForChangeUser.php';


$emptyErrors = [];
$checkErrors = [];
$renameFolder = false; 
$changeFileImageCouv = false; 
$changeFileFacture = false;
$NameFolderAndFiles = 0;
define('PATH_FORCUSTOMERS', '../../www/img/forCustomers/');



$tabChampsBdd = [];

/*=================== change User ===== */

if(isset($_POST['idCustomer']) AND !empty($_POST['idCustomer'])){
    $idUser = (int)$_POST['idCustomer'];
    if(is_int($idUser)){
        $testInfosUser = getUserById($idUser);
        if($testInfosUser){
            $infosUser = $testInfosUser;
        }
        else{
            $checkErrors['idCustomer'] = 'Erreur, aucun client ne corresspond a votre recherche';
            echo(json_encode(['error' => $checkErrors]));
            exit();
            
        }
    }
}
else{
    $checkErrors['idCustomer'] = 'Erreur, aucun client ne corresspond a votre recherche';
    echo(json_encode(['error' => $checkErrors]));
    exit();
}

$pathFolderBackupTemporary = PATH_FORCUSTOMERS . 'TEMPORARY_' . htmlspecialchars($infosUser['Identifying']) . '/';
mkdir($pathFolderBackupTemporary);


//==FILES (imageCouv et facture)============



if(isset($_FILES['facture']['tmp_name']) and !empty($_FILES['facture']['tmp_name'])){

    $type_mime_acceptFacture = ["application/pdf" => '.pdf' ];
    $validationFileFacture = controlFilesReplacing($infosUser, $pathFolderBackupTemporary, 'facture', $_FILES['facture'], 'Facture');
    
    if ($validationFileFacture == "Error upload"){
        $checkErrors['facture'] = "Probleme au téléchargement";
    }
    else if($validationFileFacture == "Error file"){
        $checkErrors['facture'] = "Fichier incorrect";
    }
    else{
        $tabChampsBdd['Facture'] = $validationFileFacture;
        $changeFileFacture = true;
        $NameFolderAndFiles += 5;
    }
}


if(isset($_FILES['imageCouv']['tmp_name']) and !empty($_FILES['imageCouv']['tmp_name'])){
    $type_mime_acceptCouv = ["image/jpg" => '.jpg', "image/jpeg" => '.jpeg', "image/gif" => '.gif', "image/png" => '.png' ];
    
    $validationFileImage = controlFilesReplacing($infosUser, $pathFolderBackupTemporary, 'imageCouv',$_FILES['imageCouv'],'ImageCouv');
    
    if ($validationFileImage == "Error upload"){
        $checkErrors['imageCouv'] = "Probleme au téléchargement";
    }
    else if($validationFileImage == "Error file"){
        $checkErrors['imageCouv'] = "Fichier incorrect";
    }
    else{
        $tabChampsBdd['ImageCouv'] =  $validationFileImage;
        $changeFileImageCouv = true;
        $NameFolderAndFiles += 3;
    }
}



/*=IDENTIFIANT=========================*/
if(isset($_POST['identifiant']) and !empty($_POST['identifiant'])){

    $testIdentifiant = Username($_POST['identifiant']);
    if($testIdentifiant){
        
        if($testIdentifiant != $infosUser['Identifying']){
            $newIdentifiant = $testIdentifiant;
            
            $newNameFolder = createNameFolder($newIdentifiant);

            $infosOldPathFolder = pathinfo($infosUser['PathFolder']);
            $oldNameFolder = $infosOldPathFolder['filename'];
            $newPathFolderForDb = str_replace($infosOldPathFolder['filename'], $newNameFolder, $infosUser['PathFolder']);
            $newPathFolder = PATH_FORCUSTOMERS . $newNameFolder;
            $oldPathFolder = PATH_FORCUSTOMERS . $infosOldPathFolder['filename'];

            rename( $oldPathFolder, $newPathFolder);
            if($changeFileImageCouv){
                $newPathOldImageCouv = str_replace( $oldNameFolder, $newNameFolder, $tabChampsBdd['ImageCouv']);
            }
            else{
                $newPathOldImageCouv = str_replace( $oldNameFolder, $newNameFolder, $infosUser['ImageCouv']);
            }

            if($changeFileFacture){
                $newPathOldFacture = str_replace( $oldNameFolder, $newNameFolder, $tabChampsBdd['Facture']);
            }
            else{
                $newPathOldFacture = str_replace( $oldNameFolder, $newNameFolder, $infosUser['Facture']);
            }
            
            if(!file_exists($newPathFolder)){ 
                $checkErrors['identifiant'] = 'nouveau dossier non créé';
            }
            else {
                $renameFolder = true;
                $NameFolderAndFiles += 1;
                $tabChampsBdd['Identifying'] = ucwords($newIdentifiant);
                $tabChampsBdd['PathFolder'] = $newPathFolderForDb;
                $tabChampsBdd['ImageCouv'] = $newPathOldImageCouv;
                $tabChampsBdd['Facture'] = $newPathOldFacture;
            }
        }
    }
    else{
        $checkErrors['identifiant'] = 'Identifiant incorrect';
    }
}
else{
    $emptyErrors['identifiant'] = 'Vous devez specifier un identifiant';
}

/*=FIRSTNAME1 et FIRSTNAME2===========================*/
if(isset($_POST['firstName1']) and !empty($_POST['firstName1'])){
    $testFirstName1 = checkFirstName($_POST['firstName1'], $infosUser['FirstName1']);
    if($testFirstName1 == 'invalid'){
        $checkErrors['firstName1'] = 'Saisie incorrect !';
    }
    else if($testFirstName1 && $testFirstName1 != 'invalid'){
        $newfirstName1 = $testFirstName1;
        $tabChampsBdd['FirstName1'] = $newfirstName1;
    }
}
else {
    $emptyErrors['firstName1'] = 'Vous devez spécifier un prenom 1.';
}

if(isset($_POST['firstName2']) and !empty($_POST['firstName2'])){
    $testFirstName2 = checkFirstName($_POST['firstName2'], $infosUser['FirstName2']);
    if($testFirstName2 == 'invalid'){
        $checkErrors['firstName2'] = 'Saisie incorrect !';
    }
    else if($testFirstName2 && $testFirstName2 != 'invalid'){
        $newfirstName2 =$testFirstName2;
        $tabChampsBdd['FirstName2'] = $newfirstName2;
    }
    
}
else {
    $emptyErrors['firstName2'] = 'Vous devez spécifier un prenom 2';
}



/*=EMAIL============================*/

if(isset($_POST['email']) and !empty($_POST['email'])){
    $testEmail = controlEmail($_POST['email']);
    if($testEmail){
        if($testEmail != $infosUser['Email']){
            $newEmail  = $testEmail;
            $tabChampsBdd['Email'] = $newEmail;
        }
    }
    else {
        $checkErrors['email'] = 'email invalide';
    }
}
else {
    $emptyErrors['email'] = 'Vous devez specifier un email';
}

/*=PHONE============================*/

if(isset($_POST['phone'])){
    if(empty($_POST['phone'])){
        if($_POST['phone'] != $infosUser['Phone']){
            $newPhone = NULL;
            $tabChampsBdd['Phone'] = $newPhone ;
        }
    }
    else {
        $postPhone = controlPhone($_POST['phone']);
        if($postPhone){
            if($postPhone != $infosUser['Phone']){
                $newPhone = $postPhone;
                $tabChampsBdd['Phone'] = $newPhone ;
            }
        }
        else{
            $checkErrors['phone'] = 'Saisie du telephone incorrect';
        } 
    }
}

/*=WEDDINGDATE=========================*/

if(isset($_POST['weddingDate']) and !empty($_POST['weddingDate'])){
    $testWeddingDate = controlWeddingDate($_POST['weddingDate']);
    if($testWeddingDate){
        if($testWeddingDate != $infosUser['WeddingDate']){
            $newWeddingDate = $testWeddingDate;
            $tabChampsBdd['WeddingDate'] = $newWeddingDate ;
        }
    }
    else{
        $checkErrors['weddingDate'] = 'Date incorrect';
    }
}
else {
    $emptyErrors['weddingDate'] = "Vous devez renseigner la date";
}

/*=LINK===============================*/
if(isset($_POST['link']) and !empty($_POST['link'])){
    $testLink = controlLink($_POST['link']);
    if($testLink){
        if($testLink != $infosUser['Link']){
            $newLink = $testLink;
            $tabChampsBdd['Link'] = $newLink;
        }
    }
    else{
        $checkErrors['link'] = 'Lien incorrect';
        $checkErrors['link'] = $_POST['link'] ;
    }   
}
else {
    $emptyErrors['link'] = "Vous devez mettre un lien";
}

    
/*=NOTE ADMIN==================*/   
if(isset($_POST['noteAdmin'])){
    if(!empty($_POST['noteAdmin'])){
        $testNoteAdmin = controlNoteAdmin($_POST['noteAdmin']);
        if($testNoteAdmin){
            if($testNoteAdmin != $infosUser['NoteAdmin']){
                $newNoteAdmin = $testNoteAdmin;
                $tabChampsBdd['NoteAdmin'] = $newNoteAdmin;
            }
        }
        else{
            $checkErrors['noteAdmin'] = "Note administrateur invalide";
        }
    }
    else{
        if($infosUser['NoteAdmin'] != ""){
            $newNoteAdmin = NULL;
            $tabChampsBdd['NoteAdmin'] = $newNoteAdmin;
        }
    }
    
}

/*===============suppretion des fichiers image et facture + envoie a la bdd====================*/

$pathFolderOriginal = preg_replace('#\/www#', '', $infosUser['PathFolder']);

if(!empty($checkErrors) || !empty($emptyErrors)){
  
    if($renameFolder){
        rename($newPathFolder, $oldPathFolder);
    }

    if($changeFileImageCouv || $changeFileFacture){
        
        deleteNewFilesFromFolderUser($infosUser, $pathFolderOriginal);
        toggleOldFilesFromFolderTemp($pathFolderBackupTemporary, $pathFolderOriginal);
    }
    else {
        emptyAndDeleteFolder($pathFolderBackupTemporary);
    }
   echo(json_encode(['checkErrors' => $checkErrors, 'emptyErrors' => $emptyErrors]));
   exit();
}
else{
    if(!empty($tabChampsBdd)){
        $updateDb = updateUserById($tabChampsBdd, $idUser);
    
        if($updateDb != 1){
            if($renameFolder){
                rename($newPathFolder, $oldPathFolder);
            }

            if($changeFileImageCouv || $changeFileFacture){
                deleteNewFilesFromFolderUser($infosUser, $pathFolderOriginal);
                toggleOldFilesFromFolderTemp($pathFolderBackupTemporary, $pathFolderOriginal);
            }
            else {
                emptyAndDeleteFolder($pathFolderBackupTemporary);
            }

            echo(json_encode(['dbError' => "Probleme l'or de l'enregistrament des modification"]));
            exit();
        }
        else{
            emptyAndDeleteFolder($pathFolderBackupTemporary);
            echo(json_encode(['valid' => 'La fiche client a bien été modifiée']));
        }   
    }
    else{
        emptyAndDeleteFolder($pathFolderBackupTemporary);
        echo(json_encode(['vide' => $_POST['link'] ]));
    }
}


