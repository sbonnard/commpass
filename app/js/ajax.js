////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Récupérer les membres d'une entreprise en AJAX.
document.getElementById('campaign_company').addEventListener('change', function () {
    var companyId = this.value;

    // On fait un appel à l'api pour récupéréer le tableau associatif encodé en json sur api.php. 
    fetch('../api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        // On entre les paramètre de recherche, ici l'identifiant de l'entreprise récupérée sur api.php.
        body: new URLSearchParams({
            'id_company': companyId
        })
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(users => {
            // On sélectionne l'input select à implémenter avec les options récupérées par l'API.
            var select = document.getElementById('campaign_interlocutor');
            select.innerHTML = ''; // Clear previous options

            // On fait une boucle pour récupérer tous les utilisateurs concernés afin de générer les options dans le DOM.
            users.forEach(user => {
                // Créer une option pour chaque utilisateur trouvé.
                var option = document.createElement('option');

                // Mettre les informations de l'utilisateur dans l'option.
                option.value = user.id_user;
                option.textContent = `${user.firstname} ${user.lastname}`;

                // Ajouter l'option à l'input select.
                select.appendChild(option);
            });
        })
        .catch(error => {
            // Si problème, la console renvoie une erreur.
            console.error('There was a problem with the fetch operation:', error);
        });
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FILTER


function fetchCampaignsByDate(dateFrom, dateTo) {
    if (!dateFrom || !dateTo) {
        console.error('Les dates doivent être fournies');
        return;
    }

    const xhr = new XMLHttpRequest();

    const url = '../api.php';

    xhr.open('POST', url, true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');


    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                console.log(response);

                if (response.length > 0) {
                    response.forEach(campaign => {
                        console.log(`Nom de la campagne: ${campaign.campaign_name}`);
                    });
                } else {
                    console.log('Aucune campagne trouvée pour cette période.');
                }
            } else {
                console.error('Erreur lors de la requête Ajax');
            }
        }
    };

    const params = `date-from=${encodeURIComponent(dateFrom)}&date-to=${encodeURIComponent(dateTo)}`;
    xhr.send(params);
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

