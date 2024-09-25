////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Get only workers from the company selected in the previous input field.
document.getElementById('campaign_company').addEventListener('change', function() {
    var companyId = this.value;

    fetch('../api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
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
        var select = document.getElementById('campaign_interlocutor');
        select.innerHTML = ''; // Clear previous options

        users.forEach(user => {
            var option = document.createElement('option');
            option.value = user.id_user;
            option.textContent = `${user.firstname} ${user.lastname}`;
            select.appendChild(option);
        });
    })
    .catch(error => {
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


    xhr.onreadystatechange = function() {
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

