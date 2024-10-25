const deleteButtons = document.querySelectorAll('.js-trash');

deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
        const operationId = this.getAttribute('data-delete-operation-id');

        fetch('../api2.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: operationId }) // Envoie l'ID à supprimer
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la suppression');
                }
                return response.json();
            })
            .then(data => {
                console.log('Élément supprimé avec succès:', data);
                // Supprimer l'élément du DOM après la réponse réussie
                const operationElement = this.closest('[data-js-operation="operation"]');
                if (operationElement) {
                    operationElement.remove();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    });
});