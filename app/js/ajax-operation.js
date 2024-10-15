////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Supprimer une opération en AJAX.

// On ajoute l'écouteur d'événement dès le chargement du DOM.
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.js-trash');
    
    // On créé une boucle pour sélectionner toutes les occurences de bouton "trash" et leur ajouter uné couteur d'événement. 
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

    // On récupère l'ID de l'opération à supprimer grace à son attribut data.
            const operationId = this.getAttribute('id');

    // On demande confirmation de la suppression à l'utilisateur.
            if (confirm('Êtes-vous sûr de vouloir supprimer cette opération ?')) {
                console.log("ID d'opération à supprimer :", operationId);
    // On appelle l'API pour supprimer l'opération sélectionné. 
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
    // Si la suppression a fonctionné, on supprime l'élément le plus proche portant l'attribut data "js-operation". 
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