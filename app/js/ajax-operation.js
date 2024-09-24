////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// DELETE OPERATION
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.js-trash');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const operationId = this.getAttribute('id');

            if (confirm('Êtes-vous sûr de vouloir supprimer cette opération ?')) {
                console.log("ID d'opération à supprimer :", operationId);
                fetch('../api.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: operationId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Réponse du serveur :", data);
                        if (data.success) {
                            alert('L\'opération a été supprimée.');
                            const operationElement = this.closest('[data-js-operation="operation"]');
                            if (operationElement) {
                                operationElement.remove();
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