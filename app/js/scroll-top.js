document.getElementById('scrollTop').addEventListener('click', function(event) {
    event.preventDefault(); // Empêche l'action par défaut
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Définit un défilement fluide
    });
});