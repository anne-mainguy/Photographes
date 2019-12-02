$(function(){

    $(document).on('click', '.btnUploadDocumentNewAccount', function(){
        $(this).prev().click();
    });
    

    $('#gestionComptes section>div').hide().removeClass('hide');
    $('#display_form_select').hide();
    


    function checkInput(element){
        let targetName = element.attr('name');
        let regex = "";
        let check;     
        
        function checkRegex(reg, target){
            if(reg.test(target.val())){
                return true;
            }
            else{
                return false;
            }
        }

        if(targetName == 'firstName1' || targetName == 'firstName2'){
            const regName = /^([a-zéèàêâäùïüëôöç]){2,}((-){1})(([a-zéèàêâäùïüëôöç]){1,})((-){1,2})([a-zéèàêâäùïüëôöç]){1,}(?!.)|^([a-zéèàêâäùïüëôöç]){2,}((-){1})([a-zéèàêâäùïüëôöç]){1,}(?!.)|^([a-zéèàêâäùïüëôöç]){2,}(?!.)/i;
            
            if(regName.test(element.val())){
                element[0].setCustomValidity('');
                check = true;
            }
            else {
                element[0].setCustomValidity('Au moins 2 carectères');
                check = false;
            }
        }
        else if(targetName == 'imageCouv' || targetName == 'facture'){
            let mime_valid = [];
            let msg_size;
            let size_valid;
            let file;
            let elt_valid;
            
            if(targetName == 'imageCouv'){
                mime_valid = [
                    "image/jpg",
                    "image/jpeg",
                    "image/gif",
                    "image/png"
                ];

                size_valid = 524288;
                msg_size = "500 ko";
                elt_valid = "COUVERTURE";
            }
            else if(targetName == 'facture'){
                mime_valid = ['application/pdf'];

                size_valid = 1048576;
                msg_size = "1 Mo";
                elt_valid = "FACTURE";
            }

            if(element[0].files[0]){

                file = element[0].files[0];
                if(mime_valid.includes(file.type)){
                    if(file.size < size_valid){
                        validFiles(element, file.name, ['greenyellow', '1.6rem', 'black' ]);
                    }
                    else {
                        size_valid / 1024
                        validFiles(element, msg_size + " maximum", ['red', '1.2rem', 'red' ], "L\'image est trop lourde");
                    }
                }else{
                    validFiles(element, "Type de fichier inccorect", ['red', '1.2rem', 'red' ], "Format de fichier incorrect");
                }
            }
            else{
                if(!element.parents('form').hasClass('createCustomer_form')){
                    if(element.parents('li').find('a').attr('href') != ''){
                        validFiles(element, elt_valid + " VALIDE", ['greenyellow', '1.6rem', 'black' ]);
                    }else {
                        validFiles(element, "Aucun fichier sélectionné", ['red', '1.2rem', 'red' ], "Vous devez selectionner une image");
                    }
                }else{
                    validFiles(element, "Aucun fichier sélectionné", ['red', '1.2rem', 'red' ], "Vous devez selectionner une image");
                }
            }
        }
        else {
            switch(targetName){
                case 'idCustomer' :
                    regex = /[0-9]*/;
                    break;
                case 'identifiant':
                    regex = /^(([A-Z])?[a-zàéèêëîïôöûüùç]+)+([ -]([A-Z])?([a-zàéèêëîïôöûüùç])*)?$/;
                    break;
                case 'password':
                    regex = /^[a-z0-9]{8,11}$/i;
                    break;
                case 'phone':
                    regex = /^([0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}( )?)$/;
                    break;
                case 'weddingDate':
                    if(element[0].type == 'date'){
                        regex  = /^20([0-9]{2}[/|-])(?![0]{2})(([0-1]{1})([0-2]{1})|([0]{1})([0-9]{1}))[/|-](?![0]{2})(([0-3]{1}[0-1]{1})|([0-2]{1}[0-9]{1}))(?!.)/;
                    }
                    else if (element[0].type == 'text'){
                        regex  = /^(?![0]{2})(([0-3]{1}[0-1]{1})|([0-2]{1}[0-9]{1}))[/|-](?![0]{2})(([0-1]{1})([0-2]{1})|([0]{1})([0-9]{1}))[/|-](20([0-9]{2}))(?!.)/;
                    }
                    break;
                case 'link':
                    regex = /^https:\/\/www.dropbox.com\/[a-zA-Z0-9-/]+(\?dl=0)$/g;
                    break;
                case 'email':
                    regex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
                    break;
                    
                default:
                    regex = /[.]/;
                    break;
            }
            
            check = checkRegex(regex, element);
        }
        
        if(targetName == 'phone' && element.val() == '' ){
            check = true;
        }
        
        if(targetName == 'phone' || targetName == 'password'){
            if(check == true ){
                element.parent().prev('p').addClass('hide');
                element.siblings('span').html('</i><i class="fas fa-check"></i>').addClass('validate');
            }
            else{
                element.parent().prev('p').removeClass('hide');
                element.siblings('span').removeClass('validate');
                element.siblings('span').text('*');
            }
        }

        if(element.siblings('p').hasClass('errorCheck') && check == true){
            element.siblings('p.errorCheck').remove();
        }
 
        
        if(check == true){
            element.siblings('span.obligatoir').html('</i><i class="fas fa-check"></i>').addClass('validate');
            element.removeClass('invalid');
        }
        else if (check == false){
            element.siblings('span.obligatoir').text('*').removeClass('validate');
            element.addClass('invalid');
        }
        
        element.siblings('span.obligatoir').removeClass('invisibility');
    }
    
    function resetForm(form){
        form[0].reset();

        validFiles(form.find('.input_image'), 'COUVERTURE', ['white', '1.6rem', 'black']);
        validFiles(form.find('.input_facture'), 'FACTURE', ['white', '1.6rem', 'black']);

        if(form.hasClass('customersExisting_form') || form.hasClass('selectCustomer_form')){
            form.find('input').attr('disabled', 'disabled');
            form.find('textarea').attr('disabled', 'disabled');
            
            form.find('.input_link').addClass('hide');
            form.find('.a_link').removeClass('hide');
            form.find('.input_modif').addClass('hide');
            form.find('.a_select').removeClass('hide');

            form.find('span.obligatoir').addClass('invisibility');
            
            form.find('.del_user').addClass('clickable');
            form.find('.change_user').removeClass('fa-times').addClass('fa-pencil-alt');
            form.find('.validate_change').removeClass('clickable'); 

            form.find('p.errorCheck').remove();
        }

        form.removeClass('editable');
        form.find('input , textarea').removeClass('invalid');
        form.find('span.obligatoir').text('*').removeClass('validate');
        form.find('p.restriction').addClass('hide');
        form.find('p.errorCheck').remove();
        
    }
 
    function infoStatus(type, message){
        $('.msg-form').addClass(type);
        $('.msg-form h3').text(message);
        $('.msg-form').fadeIn();
    }

    function emptyMsg(){
        $('.msg-form').fadeOut();
        $('.msg-form').removeClass('confirm invalid');
        $('.msg-form h3').text('');
    }
    
    function validFiles(element, message, propCss, textValidity = ""){
        element.next('span').text(message).css({color : propCss[0],
                                                    fontSize : propCss[1],
                                                    borderColor : propCss[2]});
        element[0].setCustomValidity(textValidity);
        if(textValidity == ""){
            element.removeClass('invalid');
        }
        else {
            element.addClass('invalid');
        }
    }

    function showErrors(form, key, message){
        $(form + ' input[name=' + key  +']').before('<p class="errorCheck"><span><i class="fa fa-exclamation" aria-hidden="true"></i></span> ' + message + ' <span><i class="fa fa-exclamation" aria-hidden="true"></i></span> </p> ');
        $(form + ' input[name=' + key +']').addClass('invalid');
        $(form + ' input[name=' + key +']').siblings('span').addClass('invisibility').html('');
    }

   
    function refrecheForm(){
        $("#customersExisting_content").load(location.href + " #customersExisting_content>*","");
        $("#selectCustomer_form_select").load(location.href + " #selectCustomer_form_select>*","");
    }

    
    $('#gestionComptes h2').on('click', function(){
        
        if($(this).hasClass('opened')){
            $(this).removeClass('opened');
            $(this).next('div').slideToggle();
            $('#display_form_select').slideUp();
           
            if($(this).parents('section').attr('id') == 'selectCustomer'){
                resetForm($(this).parents('section').find('form.selectCustomer_form'));
                $('#selectCustomer_form_select')[0].reset();
            }
            else {
                resetForm($(this).parents('section').find('form'));
            }
        }
        else {
            
            $('#gestionComptes h2').removeClass('opened');
            $(this).addClass('opened');

            if($('form').hasClass('editable')){
                resetForm($('form.editable'));
            } 
            
            $('#selectCustomer_form_select')[0].reset();
            $('#display_form_select').slideUp();
            $('#gestionComptes h2+div').slideUp();
            $(this).next('div').slideToggle();
        }
    });
    
    
    
    
    $(document).on('click', '#gestionComptes form i.fas', function(){
        
        $idUser = $(this).parents('form').data('iduser');
    
        if($(this).hasClass('del_user') && $(this).hasClass('clickable')){
            $(this).parents('form').addClass('outstanding-deletion');
            let confirmation = confirm('Voulez vous vraiment supprimer ce client ?');
            
            if(confirmation == true){
                $.ajax({
                    url : '../www/AJAX/deleteUser.AJAX.php',
                    type : 'POST',
                    dataType : 'text',
                    data : {
                        'idUser' : $idUser
                    },
                    success : function(data){
                        if(data == 1){
                            infoStatus('confirm', 'Le dossier a bien été supprimé.');
                            window.setTimeout(emptyMsg, 2000);
                            if($('form.selectCustomer_form').hasClass('outstanding-deletion')){
                                $('#display_form_select').slideUp();
                                $('.input_select').val('');
                            }
                            $('form.outstanding-deletion').removeClass('outstanding-deletion');
                            refrecheForm();
                        }
                        else{
                            infoStatus('invalid', "Une erreur c'est produite.");
                            window.setTimeout(emptyMsg, 3000);
                        }                       
                    },
                    error : function(error){
                    }
                });        
            }else {
                $(this).parents('form').removeClass('outstanding-deletion');
            }
        }
      
        if($(this).hasClass('change_user')){
                if($(this).hasClass('fa-pencil-alt')){
                    $(this).removeClass('fa-pencil-alt');
                    $(this).addClass('fa-times');
                }
                else {
                    $(this).removeClass('fa-times');
                    $(this).addClass('fa-pencil-alt');
                }
                                
                if($(this).hasClass('fa-times')){
                    if($('form').hasClass('editable')){
                        resetForm($('form.editable'));
                        $('form.editable').removeClass('editable');
                    }

                    
                    $(this).parents('form').addClass('editable');

                    $(this).parents('form').find('input').removeAttr('disabled');
                    $(this).parents('form').find('textarea').removeAttr('disabled');
                    
                    $(this).parents('form').find('.a_link').addClass('hide');
                    $(this).parents('form').find('.input_link').removeClass('hide');
                    $(this).parents('form').find('.a_select').addClass('hide');
                    $(this).parents('form').find('.input_modif').removeClass('hide');

                    $(this).siblings('.validate_change').addClass('clickable');
                    $(this).siblings('.del_user').removeClass('clickable');
                    
                }
                else {                    
                    resetForm($(this).parents('form'));
                    $(this).parents('form').removeClass('editable');
                    
                    if($(this).parents('form').hasClass('selectCustomer_form')){
                        $('#display_form_select').slideUp();
                    }
                }
                
        }
        
        if($(this).hasClass('fa-check') && $(this).hasClass('clickable')){
            $formParent = $(this).parents('form');
            $formParentInputs = $formParent.find('input').not('[name=MAX_FILE_SIZE]');
            
            $formParentInputs.each(function(){
                if(!$(this)[0] == $('form.editable input[name=imageCouv]')[0] || !$(this)[0] == $('form.editable input[name=facture]')[0])
                {
                    checkInput($(this));
                }
            });
            
            if($(this).parents('form').find('input.invalid').length <= 0){
                if($(this).parents('form').find('[name=weddingDate]')[0].type == 'text'){
                    fullDate = $(this).parents('form').find('[name=weddingDate]').val().split('/');
                    $(this).parents('form').find('[name=weddingDate]').val(fullDate[2] + '-' + fullDate[1] + '-' + fullDate[0]);
                }   

                let formData = new FormData(document.querySelector('form.editable'));
                $.ajax({
                    url : '../www/AJAX/changeUser.AJAX.php',
                    type : 'POST',
                    dataType: "json", 
                    data : formData,
                    processData: false, 
                    contentType: false, 
                    success : function(data){                        
                        $('form.editable p.errorCheck').remove();                       
                        if(data.emptyErrors || data.checkErrors){
                            let keysCheck = Object.keys(data.checkErrors);
                            for(let i = 0 ; i < keysCheck.length; i++){                              
                                if(keysCheck[i] == "imageCouv" || keysCheck[i] == "facture"){
                                    validFiles($('form.editable input[name=' + keysCheck[i] ), data.checkErrors[keysCheck[i]], ['red', '1.2rem', 'red' ], "Vous devez selectionner un fichier");
                                }
                                else {
                                    showErrors('form.editable', keysCheck[i], data.checkErrors[keysCheck[i]]); 
                                }
                            }

                            let keysEmpty = Object.keys(data.emptyErrors);
                            for(let i = 0 ; i < keysEmpty.length ; i++){
                                    $('form.editable input[name=' + keysEmpty[i] + ']').addClass('invalid');
                                    $('form.editable input[name=' + keysEmpty[i] + ']').siblings('span').removeClass('invisibility validate').text('*');
                            }
                        }
                        else if(data.valid){
                            infoStatus('confirm', 'La fiche a bien été modifiée.');
                            window.setTimeout(emptyMsg, 2000);
                            if($('form.selectCustomer_form').hasClass('editable')){
                                $('#display_form_select').slideUp();
                                $('.input_select').val('');
                            }
                            refrecheForm();
                        }
                        else if(data.vide){
                            resetForm($('form.editable'));
                        }
                        else if(data.dbError){
                            infoStatus('invalide'," Une erreur c'est produite.");
                            window.setTimeout(emptyMsg, 3000)
                        }
                    },
                    error : function(error){
                        infoStatus('invalide'," Une erreur c'est produite.");
                        window.setTimeout(emptyMsg, 3000);
                    }
                });
            }
        }
        
    });
    
    

    $(document).on('focus', '.input_select', function(){
        $('.input_select').not(':focus').val('');
    });
    

    $(document).on('input', '.input_select', function(){
        let valeur = $(this).val();
        let options;
        
        options = $(this).next('datalist').find('option');

        for(var i = 0; i < options.length ; i++){
            if(options[i].value.toLowerCase() == valeur.toLocaleLowerCase()){
                id = options[i].getAttribute('data-id');
                
                $.ajax({
                    url : '../www/AJAX/selectClient.php',
                    type : "POST",
                    dataType : "json",
                    data : {"idUser" : id},
                    success : function(data){
                        resetForm($('#form_selectCustomer'));
                        $('#form_selectCustomer').attr('data-iduser', data.pqjae_ID);
                        $('#selectId').val(data.pqjae_ID);
                        $('#selectUsername').val(data.pqjae_Identifying);
                        $('#selectFirstname1').val(data.pqjae_FirstName1);
                        $('#selectFirstname2').val(data.pqjae_FirstName2);
                        $('#selectEmail').val(data.pqjae_Email);
                        if(data.pqjae_Phone != null ){
                            $phone = data.pqjae_Phone.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
                            $('#selectTel').val($phone);
                        }
                        else {
                            $('#selectTel').attr('placeholder', 'aucun telephone');
                        }
                        $('#selectDate').val(data.pqjae_WeddingDate);
                        data.pqjae_Link != null?
                            $('#selectLink').val(data.pqjae_Link):
                            $('#selectLink').attr('placeholder', 'pas de lien');
                        $('#a_selectLink').attr('href', data.pqjae_Link);
                        $('#input_selectLink').val(data.pqjae_Link);
                        $('#a_selectImage').attr('href', data.pqjae_ImageCouv);
                        $('#a_selectFacture').attr('href', data.pqjae_Facture);
                        data.pqjae_NoteCustomer != null?
                            $('#selectNoteUser').text(data.pqjae_NoteCustomer):
                            $('#selectNoteUser').text("Aucun commentaire client");
                        data.pqjae_NoteAdmin != null ? 
                            $('#selectNoteAdmin').val(data.pqjae_NoteAdmin):
                            $('#selectNoteAdmin').val("Aucun commentaire administrteur");

                        $('#display_form_select').slideDown();
                        $('.input_select').val('');
                    },
                    error : function(error){
                    }
                });
            } 
        } 
    });
    

     
    $('#form_createCustomer input,#form_createCustomer textearea').on('input', function(){
        $('#form_createCustomer').addClass('editable');
    });
    
    
    $('#form_createCustomer button[name=submit]').on('click', function(e){
        e.preventDefault();
        $formParent = $(this).parents('form');
        $(this).parent('form').addClass('editable');
        
        $('#form_createCustomer input').not('[name=MAX_FILE_SIZE]').each(function(){
            checkInput($(this));
        });
                
        if($('#form_createCustomer input.invalid').length == 0){
            let formData = new FormData(document.getElementById('form_createCustomer'));
            $.ajax({
                url : '../www/AJAX/newCustomer.AJAX.php',
                type : 'POST',
                dataType : "json",
                data : formData,
                processData: false,
                contentType: false,
                success : function(data){
                    $('#form_createCustomer p.errorCheck').remove();

                    if(data.success){
                        resetForm($('#form_createCustomer'));
                        infoStatus('confirm', 'La fiche a bien été créée.');
                        refrecheForm();
                        window.setTimeout(emptyMsg, 2000);
                        
                    }
                    else{
                        if(data.errorsFiles){
                            let keysFiles = Object.keys(data.errorsFiles);
                            for(key of keysFiles){                                
                                validFiles($('#form_createCustomer input[name=' + key + ']'), data.errorsFiles[key], ['red', '1.2rem', 'red'], 'Erreur fichier');
                            } 
                        }

                        if(data.errors){
                            let keysError = Object.keys(data.errors);
                            for(key of keysError){
                                
                                showErrors('#form_createCustomer', key, data.errors[key]);
                            } 
                        }
                    }
                },
                error : function(error){
                    infoStatus('invalide'," Une erreur c'est produite.");
                    window.setTimeout(emptyMsg, 3000);
                }
            });
        }
        else{
            e.preventDefault();
        }
    });


    $(document).on('keyup', '#gestionComptes input[name=phone]', function(){
        let phone = $(this).val();
        phone = phone.replace(/ /g, '');
        function formatPhone(tel){
            return tel.replace(/(\d\d(?!$))/g,"$1 ");
        } 
        $(this).val(formatPhone(phone));
    })
    
    
    $(document).on('input', '#gestionComptes input:not(input.input_select)', function(){
        checkInput($(this));
    });
    
    
});
