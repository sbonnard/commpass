// NEW MEDIA AJAX

const newMediaForm = document.getElementById('new-media-form');
const mediaSelectElement = document.getElementById('operation_media');
const addMediaInput = document.getElementById('add-media');

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
            alert('Média ajouté avec succès');

            let newOption = document.createElement('option');
            newOption.value = data.media_id; // L'ID du média ajouté retourné par l'API
            newOption.textContent = addMedia; // Le nom du média ajouté
            mediaSelectElement.appendChild(newOption); // Ajoute l'option à la liste déroulante
            mediaSelectElement.value = data.media_id; // Sélectionne automatiquement le nouveau média dans le select
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur lors de la requête:', error);
    });
});

// NEW PARTNER AJAX

const newPartnerForm = document.getElementById('new-partner-form');
const partnerSelectElement = document.getElementById('operation_partner');
const addPartnerInput = document.getElementById('add-partner');

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
            alert('Partenaire ajouté avec succès');

            let newOption = document.createElement('option');
            newOption.value = data.partner_id; // L'ID du partenaire ajouté retourné par l'API
            newOption.textContent = addPartner; // Le nom du partenaire ajouté
            partnerSelectElement.appendChild(newOption); // Ajoute l'option à la liste déroulante
            partnerSelectElement.value = data.partner_id; // Sélectionne automatiquement le nouveau partenaire dans le select
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur lors de la requête:', error);
    });
});