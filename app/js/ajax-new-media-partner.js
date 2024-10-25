// GESTION OVERLAYS DES FORMULAIRES

// NEW MEDIA
const newMediaLnk = document.getElementById('media-lnk');
const newMediaForm = document.getElementById('new-media-form');
const mediaSelectElement = document.getElementById('operation_media');
const addMediaInput = document.getElementById('add-media');

// NEW MEDIA AJAX

// FERMER L'OVERLAY SI ON CLIC EN DEHORS DE CELUI-CI.
document.addEventListener('click', function (event) {
    if (!newMediaForm.contains(event.target) && !newMediaLnk.contains(event.target)) {
        newMediaForm.classList.add('hidden');
    }
});

// OUVRIR L'OVERLAY SI ON CLICK SUR LE LIEN DE CRÉATION DE PARTENAIRE.
newMediaLnk.addEventListener('click', function (e) {
    e.preventDefault(); // Empêche le comportement par défaut de soumission du formulaire

    newMediaForm.classList.toggle('hidden'); // Masque le formulaire de création de partenaire
});


newMediaForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche le comportement par défaut de soumission du formulaire

    let addMedia = addMediaInput.value; // Récupère la valeur du champ input

    fetch('../api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'add-media': addMedia,
            'action': 'add-media'
        })
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            console.log(data); // Affiche la réponse pour voir le message de succès ou d'erreur
            if (data.status === 'success') {
                let newOption = document.createElement('option');
                newOption.value = data.media_id; // L'ID du média ajouté retourné par l'API
                newOption.textContent = addMedia; // Le nom du média ajouté
                mediaSelectElement.appendChild(newOption); // Ajoute l'option à la liste déroulante
                mediaSelectElement.value = data.media_id; // Sélectionne automatiquement le nouveau média dans le select
                newMediaForm.reset(); // Vide le champ input pour une nouvelle saisie
                newMediaForm.classList.add('hidden'); // Cache de nouveau le formulaire après validation.
                newPartnerForm.classList.add('hidden'); // Cache de nouveau le formulaire de partenaire s'il est en dessous après validation.
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête:', error);
        });
});

// NEW PARTNER
const newPartnerLnk = document.getElementById('partner-lnk');
const newPartnerForm = document.getElementById('new-partner-form');
const partnerSelectElement = document.getElementById('operation_partner');
const addPartnerInput = document.getElementById('add-partner');


// FERMER L'OVERLAY SI ON CLIC EN DEHORS DE CELUI-CI.
document.addEventListener('click', function (event) {
    if (!newPartnerForm.contains(event.target) && !newPartnerLnk.contains(event.target)) {
        newPartnerForm.classList.add('hidden');
    }
});

// OUVRIR L'OVERLAY SI ON CLICK SUR LE LIEN DE CRÉATION DE PARTENAIRE.
newPartnerLnk.addEventListener('click', function (e) {
    e.preventDefault(); // Empêche le comportement par défaut de soumission du formulaire

    newPartnerForm.classList.toggle('hidden'); // Masque le formulaire de création de partenaire
});

// NEW PARTNER AJAX

newPartnerForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche le comportement par défaut de soumission du formulaire

    let addPartner = addPartnerInput.value; // Récupère la valeur du champ input

    fetch('../api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'

        },
        body: JSON.stringify({
            'add-partner': addPartner,
            'action': 'add-partner'
        })
    })
        .then(response => {
            // console.log('Raw response:', response);
            if (response.ok) {
                // console.log('ERROR RESPONSE JSON:', response.json());
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            console.log(data);
            if (data.status === 'success') {
                let newOption = document.createElement('option');
                newOption.value = data.partner_id; // L'ID du partenaire ajouté retourné par l'API
                newOption.textContent = addPartner; // Le nom du partenaire ajouté
                partnerSelectElement.appendChild(newOption); // Ajoute l'option à la liste déroulante
                partnerSelectElement.value = data.partner_id; // Sélectionne automatiquement le nouveau partenaire dans le select
                newPartnerForm.reset(); // Vide le champ input pour une nouvelle saisie
                newPartnerForm.classList.add('hidden'); // Cache de nouveau le formulaire après validation.
                newMediaForm.classList.add('hidden'); // Cache de nouveau le formulaire de media si il est en dessous après validation.
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête:', error);
        });
});