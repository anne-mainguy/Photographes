"use strict"
window.addEventListener('load', function(){
    
    const section = document.querySelectorAll('#faq section'),
          titre = document.querySelectorAll('#faq section h2'),
          contenu = document.querySelectorAll('#faq section div');
    
    for(var i = 0; i < section.length; i++){
        titre[i].style.cursor = 'pointer';
        titre[i].addEventListener('click', showContenu);
        contenu[i].style.display = 'none';
    }
     
    function showContenu(){
        if(this.nextElementSibling.style.display == 'none'){
            this.nextElementSibling.style.display = 'block';
        }
        else{
            this.nextElementSibling.style.display = 'none';
        }
    }

    
});