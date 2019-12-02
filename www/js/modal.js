'use strict'

const lienModal = document.getElementById('link_loginUser'),
      modal_loginUser = document.getElementById('modal_loginUser'),
      modal_header = document.getElementsByClassName('modal_header'),
      btnClose = document.getElementById('close_modal'),
      formLogin = document.getElementById('formLogin'),
      btnForLoginAdmin = document.getElementById('accessLoginAdmin'),
      inputPseudo = document.getElementById('pseudo'),
      inputPassword = document.getElementById('mdp'),
      message = document.getElementById('mess_error_login'),
      indicationLogin = document.getElementById('msgIndicationLogin');



function openLogin(e){

    if(this.getAttribute('href') == '#'){
        e.preventDefault();
        modal_loginUser.style.display = 'block';
        modal_loginUser.classList.remove('close');
        modal_loginUser.classList.add('open');

        if(modal_header[0].classList.contains('admin')){
            modal_header[0].classList.remove('admin');
            modal_header[0].classList.add('customer');
            indicationLogin.textContent = 'Un identifiant et un mot de passe vous a été fourni uniquement si vous êtes déjà client.';
            formLogin.setAttribute('data-traitement', "/CONTROLLER/connectUser.php"); 
        }        
    }
}

function closeModal(){
    inputPseudo.value = '';
    inputPassword.value = '';

    if(!message.classList.contains('invisibility')){
        message.classList.add('invisibility');
    }
    modal_loginUser.style.display = 'none'; 
    modal_loginUser.classList.remove('open');
    modal_loginUser.classList.add('close')
}

btnClose.addEventListener('click', closeModal);
    

btnForLoginAdmin.addEventListener('click', function(){
    if(this.parentElement.classList.contains('admin')){
        this.parentElement.classList.remove('admin');
        this.parentElement.classList.add('customer');
        formLogin.setAttribute('data-traitement', "/CONTROLLER/connectUser.php");
        indicationLogin.textContent = 'Un identifiant et un mot de passe vous a été fourni uniquement si vous êtes déjà client.';     
    }else{
        this.parentElement.classList.remove('customer');
        this.parentElement.classList.add('admin');
        formLogin.setAttribute('data-traitement', "/CONTROLLER/connectAdmin.php"); 
        indicationLogin.textContent = 'Uniquement pour les administrateurs';     
    }
    inputPseudo.value = '';
    inputPassword.value = '';
    if(!message.classList.contains('invisibility')){
        message.classList.add('invisibility');
    }
});


modal_loginUser.addEventListener('click', function(e){
    var target = e.target; 
    if(target == modal_loginUser){ 
        closeModal();
    }
    
});




