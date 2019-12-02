"use strict"

function stateIconNav(e){
    e.preventDefault();

    const nav = document.getElementById('nav');

    this.classList.toggle('is_closed');
    this.classList.toggle('is_opened');

    if(this.classList.contains('is_opened')){
        nav.classList.add('opened');
    }else {
        nav.classList.remove('opened');
    }

};