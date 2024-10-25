import './../scss/style.scss';

// MAKE DISAPPEAR A MESSAGE AFTER A CERTAIN TIME //
document.addEventListener('DOMContentLoaded', function () {
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





