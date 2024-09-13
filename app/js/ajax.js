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