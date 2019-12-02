"use strict"

window.addEventListener('load', function(){
    
    const container = document.getElementById('container');
    const icon = document.getElementById('icon-nav');
    const lienModal = document.getElementById('link_loginUser');

    container.style.display = "block";
    icon.addEventListener('click', stateIconNav);
    lienModal.addEventListener('click', openLogin);
});