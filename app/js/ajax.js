document.getElementById('company').addEventListener('change', function() {
    var companyId = this.value;

    // Requête AJAX vers getUsersByCompany.php pour récupérer les interlocuteurs
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../api.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            var users = JSON.parse(this.responseText);
            var select = document.getElementById('interlocutor');
            select.innerHTML = ''; // Efface les options existantes

            // Ajoute les nouvelles options
            users.forEach(function(user) {
                var option = document.createElement('option');
                option.value = user.id_user;
                option.textContent = user.firstname;
                select.appendChild(option);
            });
        }
    };
    xhr.send('id_company=' + companyId);
});