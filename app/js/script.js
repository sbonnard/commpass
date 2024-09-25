import './../scss/style.scss';


// Hamburger Navigation //

const burgerMenu = document.getElementById('hamburger-menu-icon');

const overlay = document.getElementById('menu');

burgerMenu.addEventListener('click', function () {
    this.classList.toggle("close");
    overlay.classList.toggle("overlay");
    overlayConnection.classList.remove("overlay");
});

// MAKE DISAPPEAR A MESSAGE AFTER A CERTAIN TIME //
document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = 0;
            setTimeout(() => {
                errorMessage.remove();
            }, 600); 
        }, 8000);
    }

    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = 0;
            setTimeout(() => {
                successMessage.remove();
            }, 600); 
        }, 8000);
    }
});


// Connection Menu //

const ConnectionLink = document.getElementById('connection-menu');

const overlayConnection = document.getElementById('connection-form');

ConnectionLink.addEventListener('click', function () {
    this.classList.toggle("close");
    overlay.classList.remove("overlay");
    overlayConnection.classList.toggle("overlay");
});


