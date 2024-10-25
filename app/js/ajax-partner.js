////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// DELETE PARTNER
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.js-trash');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const partnerId = this.getAttribute('id');

            if (confirm('Êtes-vous sûr de vouloir supprimer cette opération ?')) {
                console.log("ID d'opération à supprimer :", partnerId);
                fetch('../api.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: partnerId,
                        action: 'delete_partner'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Réponse du serveur :", data);
                        if (data.success) {
                            alert('L\'opération a été supprimée.');
                            const partnerElement = this.closest('[data-js-partner="partner"]');
                            if (partnerElement) {
                                partnerElement.remove();
                            }
                        } else {
                            alert('Erreur lors de la suppression : ' + (data.message || 'Erreur inconnue.'));
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        });
    });
});