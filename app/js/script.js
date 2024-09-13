import './../scss/style.scss';


// Hamburger Navigation //

const burgerMenu = document.getElementById('hamburger-menu-icon');

const overlay = document.getElementById('menu');

burgerMenu.addEventListener('click', function () {
    this.classList.toggle("close");
    overlay.classList.toggle("overlay");
    overlayConnection.classList.remove("overlay");
});


// Connection Menu //

const ConnectionLink = document.getElementById('connection-menu');

const overlayConnection = document.getElementById('connection-form');

ConnectionLink.addEventListener('click', function () {
    this.classList.toggle("close");
    overlay.classList.remove("overlay");
    overlayConnection.classList.toggle("overlay");
});