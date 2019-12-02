'use strict'

window.addEventListener('load', function(){

    const option = document.getElementsByName('option');

    const videoLongElt = document.getElementById('videoLongue'),
          videoCourteElt = document.getElementById('videoCourte');
    const ceremonieElt = document.getElementById('ceremonies'),
          soireeElt = document.getElementById('soiree');
    const optionVideo = document.getElementsByName('optionVideo');

    const btnPromo = document.getElementById('applyPromo');

    let totalHT = 0,
        totalHT_outVideoLong = 0,
        TVA = 0,
        totalTTC = 0,
        remise = 0,
        reduction = 0;

    const affHTAvRemise = document.getElementById('affHCAvRemise'),
          affHT = document.getElementById('affHC'),
          affTAXE = document.getElementById('affCharge'),
          affTTC = document.getElementById('affTotal');

    let i;




    function selectOption(){
        let pricePrestation = parseFloat(this.value);
        let filmingPrestation = 0;
        let hoursFilming = parseFloat(videoLongElt.getAttribute('data-combinesHoursVideo'));

        if(this.hasAttribute('data-filming')){
            filmingPrestation = parseFloat(this.getAttribute('data-filming'));
        }

        if(this.hasAttribute('checked')){

            this.removeAttribute('checked');
            videoLongElt.setAttribute('data-combinesHoursVideo', (hoursFilming - filmingPrestation));
            videoLongElt.value = videoLongElt.getAttribute('data-combinesHoursVideo') * 150;

            if((!ceremonieElt.checked && !soireeElt.checked) && !videoLongElt.hasAttribute('disabled')){
                videoLongElt.setAttribute('disabled', 'disabled');
                videoLongElt.nextElementSibling.classList.add('opacityMin');
                videoLongElt.removeAttribute('checked');
                videoLongElt.checked = false;
            }

            calcule(-pricePrestation);
            
        }else {
            this.setAttribute('checked', 'true');

            videoLongElt.setAttribute('data-combinesHoursVideo', (hoursFilming + filmingPrestation));
            videoLongElt.value = videoLongElt.getAttribute('data-combinesHoursVideo') * 150;

            if((this == ceremonieElt || this == soireeElt) && videoLongElt.hasAttribute('disabled')){
                videoLongElt.removeAttribute('disabled');
                videoLongElt.nextElementSibling.classList.remove('opacityMin');
            }

            calcule(pricePrestation)
        }
    }


    function selectOptionVideo(){

        if(!this.hasAttribute('checked')){

            if(this == videoCourteElt){

                if(videoLongElt.hasAttribute('checked')){
                    videoLongElt.removeAttribute('checked');
                    videoLongElt.checked = false;
                }
                calcule(parseFloat(videoCourteElt.value));
            }else {
                if(videoCourteElt.hasAttribute('checked')){
                    videoCourteElt.removeAttribute('checked');
                    videoCourteElt.checked = false;
                    calcule(parseFloat(-videoCourteElt.value));
                }
                calcule(0);
            }

            this.setAttribute('checked', 'true');
            this.checked = true;      

        }else {
            this.removeAttribute('checked');
            this.checked = false;

            if(this == videoCourteElt){
                calcule(parseFloat(-videoCourteElt.value));
            }else {
                calcule(0);
            }
        }

    }


    function promo(){
        $.post(
            '../CONTROLLER/promo.php',
            {
                codePromo : $('#codePromo').val().toLowerCase(),
            },
            function(data){
                if(data != 0){
                    remise = data;
                    calcule(0);
                    btnPromo.removeEventListener('click',promo);
                    $('#applyPromo').addClass('validated');
                    $('#applyPromo').css('cursor', 'default');
                    
                    $('#tauxRemise').text(data);
                    $('.messValidation').removeClass('invisibility');
                    $('#codePromo').attr('disabled', 'disabled');
                }else {
                    $('#codePromo').val("");
                    $('#codePromo').attr('placeholder', 'code non valide');
                }
            }
        );
    }



    function calcule(pricePresta){
        totalHT_outVideoLong = totalHT_outVideoLong + pricePresta;

        if(videoLongElt.checked){
            let priceVideoLong = parseFloat(videoLongElt.value);
            totalHT = totalHT_outVideoLong + priceVideoLong;
        }else {
            totalHT = totalHT_outVideoLong ;
        }
        if(remise > 0){
            affHTAvRemise.innerHTML = totalHT + ' €';
            affHTAvRemise.parentElement.classList.remove('invisibility');
            reduction = (totalHT / 100) * parseFloat(remise);
            totalHT = totalHT - reduction;
        }

        TVA = (totalHT / 100) * 23;
        totalTTC = totalHT + TVA;

        affHT.innerHTML = totalHT.toFixed(2) + ' €';
        affTAXE.innerHTML = TVA.toFixed(2) + ' €';
        affTTC.innerHTML = totalTTC.toFixed(2) + ' €';       
    }



    for(i = 0; i < option.length ; i++){
        option[i].addEventListener('change', selectOption);                        
    }

    for(i = 0 ; i < optionVideo.length ; i++){
        optionVideo[i].addEventListener('change', selectOptionVideo);
    }

    if(btnPromo){
        btnPromo.addEventListener('click', promo);
    }
    
    
});
