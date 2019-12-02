"use strict"

window.addEventListener('load', function(){

//variables générales    
    let boxBackground = document.getElementById('boxBackground'),
        slideImage = document.querySelector('#containerSlideImage img'),
        closingLightbox = document.getElementById('closingLightbox'),
        bodyElt = document.body,
        header = document.getElementById('top'),
        gallery = document.querySelector('section.galerie'),
        images = document.querySelectorAll('.containerImages a');
        
    
//variable lightbox
    let previousBtn = document.querySelector('.fa-chevron-circle-left'),
        nextBtn = document.querySelector('.fa-chevron-circle-right'),
        currentImageIndex,
        currentImageUrl;
    
//variable formulaires 
//===ajout
    let btnShowFormAdd = document.getElementById('btn_add'),
        formUpload = document.querySelector('#form_upload form'),
        btnUpload = document.getElementById('uploadImagesGalerie'),
        inputUpload = document.getElementById('upload_files'),
        divContainerAdd = document.getElementById('form_upload'),
        formAdd_open = false,
        pInfosFiles = document.getElementById('infos_files'),
        pErrorsFiles = document.getElementById('errors_files'),
        ulInfosFiles = document.getElementById('infos_name_files'),
        nbFiles,
        sizeFiles;
//===supp
    let btnShowFormSup = document.getElementById('btn_sup'),
        btnSubmitFormSup = document.getElementById('submit_sup'),
        selection = document.querySelectorAll('.containerImages input'),
        formSupp_open = false;
    

    
    function openLightbox(e){
        e.preventDefault();

        let index = this.getAttribute('data-item');
        let url = this.getAttribute('data-url');

        header.classList.add('flou'); 
        gallery.classList.add('flou'); 
        bodyElt.style.overflow = "hidden"; 

        creatImageLightbox(url, index);
        
        boxBackground.style.display = 'block';

        window.addEventListener('click', function(e){
            let target = e.target;
            if(target == boxBackground){
               closeLightbox();
            }else if(target == nextBtn){
                nextImage();
            }else if(target == previousBtn){
                previousImage();
            }else if(target == closingLightbox){
                closeLightbox();
            }
        });

    }
    
    function closeLightbox(){
        header.classList.remove('flou');
        gallery.classList.remove('flou');
        bodyElt.style.overflow = "auto";
        
        slideImage.id = "";
        slideImage.src = "";
        slideImage.setAttribute('data-item', "");
        
        boxBackground.style.display = 'none';
    }
    
    function creatImageLightbox(url, index){
        slideImage.id = 'current'; 
        slideImage.src = url;
        slideImage.setAttribute('data-item', index);
    }
        
    function previousImage(){
        currentImageIndex = slideImage.getAttribute('data-item');
        currentImageIndex = parseInt(currentImageIndex) - 1;
        if(currentImageIndex == -1){
            currentImageIndex = images.length - 1;
        }
        currentImageUrl = images[currentImageIndex].getAttribute('data-url');
        
        creatImageLightbox(currentImageUrl, currentImageIndex);  
    }
    
    function nextImage(){
        currentImageIndex = slideImage.getAttribute('data-item'); 
        currentImageIndex = parseInt(currentImageIndex) + 1; 
        if(currentImageIndex == images.length){
            currentImageIndex = 0;
        }
        currentImageUrl = images[currentImageIndex].getAttribute('data-url');
        
        creatImageLightbox(currentImageUrl, currentImageIndex);
    }
    


   
    for(let i = 0 ; i < images.length ; i++){
        images[i].addEventListener('click', openLightbox);
    }


    if(window.location.hash == '#admin'){   

        for(let i = 0; i < selection.length ; i++){
            selection[i].addEventListener('change', markSelection);
        }        
        
        for(let i = 0 ; i < images.length ; i++){
            images[i].removeEventListener('click', openLightbox);
            images[i].addEventListener('click', function(e){
               e.preventDefault();
            });
        }
        
        function markSelection(){
            if(!this.classList.contains('select')){
                this.classList.add('select');
                this.parentElement.classList.add('mark');
            }else{

                this.classList.remove('select');
                this.parentElement.classList.remove('mark');
            }
        }                
        
        function suppSelection(elt){
            elt.parentElement.classList.remove('mark');
            elt.previousElementSibling.classList.remove('select');
            elt.previousElementSibling.checked = false;
        }

        function clickCheckboxSup(){
            this.previousElementSibling.click();
        }        

        btnShowFormSup.addEventListener('click', function(){

            for(let i = 0; i < selection.length; i++){
                selection[i].classList.toggle('hide');
            }

            if(formSupp_open == false){
                formSupp_open = true;
                for(let i = 0 ; i < images.length ; i++){
                    images[i].addEventListener('click', clickCheckboxSup);
                }
            }else {
                formSupp_open = false;
                for(let i = 0 ; i < images.length ; i++){
                    images[i].removeEventListener('click', clickCheckboxSup);
                    suppSelection(images[i]);
                }
            }

            if(formAdd_open == true){
                divContainerAdd.classList.add('hide');
                formAdd_open = false;
            }

            btnSubmitFormSup.classList.toggle('invisibility');
        });    
           
        function clickInputUpload(){
            inputUpload.click();
        }    

        btnUpload.addEventListener('click', clickInputUpload);        
        
        inputUpload.onchange = function()
        {
            pInfosFiles.textContent = "";
            pErrorsFiles.textContent = "";
            ulInfosFiles.innerHTML = "";

            sizeFiles = 0;
            nbFiles = 0;
            let listFiles = inputUpload.files;

            for ( let i = 0; i < listFiles.length; i ++ )
            {
                let file = listFiles[i];
                let nameFile
                
                let sizeFile = Math.ceil(file.size / 1024);

                nbFiles += 1 ;
                sizeFiles += sizeFile;
                nameFile = document.createElement('li');
                
                nameFile.textContent = file.name;
                if(nbFiles > 5 || file.size > 524288) {
                    nameFile.classList.add('invalid');
                    if(nbFiles > 5){
                        nbFiles -= 1;
                        sizeFiles -= sizeFile;
                    }
                }
                ulInfosFiles.appendChild(nameFile);
            }
            if(sizeFiles > 2500){
                pErrorsFiles.textContent = "Poids maximum dépasé. Merci de faire une nouvelle selection !";
                inputUpload.value = "";
                formUpload.reset();
            }
            else {
                pInfosFiles.textContent = nbFiles + ' fichiers sélécrtionnés : ' + sizeFiles  + ' ko / 2500 ko';
                btnUpload.removeEventListener('click', clickInputUpload);
                btnUpload.style.backgroundColor = "#c5d3e4";
                btnUpload.style.color = "white";
            }

        };

        btnShowFormAdd.addEventListener('click', function(){
            divContainerAdd.getElementsByTagName('form')[0].reset();
            pInfosFiles.textContent = "";
            ulInfosFiles.innerHTML = "";
            nbFiles = 0 ;
            sizeFiles = 0;

            btnUpload.addEventListener('click', clickInputUpload);
            btnUpload.style.backgroundColor = "white";
            btnUpload.style.color = "#ff71ad";

            divContainerAdd.classList.toggle('hide');
            
            if(formAdd_open == false){
                formAdd_open = true;
            }else {
                formAdd_open = false;
            }
            
            if(formSupp_open == true){
                for(let i = 0; i < selection.length; i++){
                selection[i].classList.add('hide');
                }
                for(let i = 0 ; i < images.length ; i++){
                    images[i].removeEventListener('click', clickCheckboxSup);
                    suppSelection(images[i]);
                }
                btnSubmitFormSup.classList.add('invisibility');
                formSupp_open = false;
            }
        });
    } 

    
});