document.getElementById('campaign_company').addEventListener('change', function() {
    var companyId = this.value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../api.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            var users = JSON.parse(this.responseText);
            var select = document.getElementById('campaign_interlocutor');
            select.innerHTML = '';

            users.forEach(function(user) {
                var option = document.createElement('option');
                option.value = user.id_user;
                option.textContent = user.firstname;
                option.textContent += ' ';
                option.textContent += user.lastname;
                select.appendChild(option);
            });
        }
    };
    xhr.send('id_company=' + companyId);
});


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
