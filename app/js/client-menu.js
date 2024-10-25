const plusButtonClient = document.querySelector('[data-plus-button]');
const clientMenu = document.querySelector('[data-client-menu]');

// Afficher le menu au survol du bouton plus
plusButtonClient.addEventListener('mouseenter', function (e) {
    e.preventDefault();
    clientMenu.style.display = 'block';
});

// Masquer le menu lorsqu'on quitte le bouton plus
plusButtonClient.addEventListener('mouseleave', function (e) {
    setTimeout(() => {
        if (!clientMenu.matches(':hover')) {
            clientMenu.style.display = 'none';
        }
    }, 100);
});

// Garder le menu visible tant qu'il est survolé
clientMenu.addEventListener('mouseenter', function (e) {
    clientMenu.style.display = 'block';
});

// Masquer le menu lorsque la souris quitte le menu
clientMenu.addEventListener('mouseleave', function (e) {
    clientMenu.style.display = 'none';
});