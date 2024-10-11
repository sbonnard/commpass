document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#formPDF').addEventListener('submit', function(event) {
        var content = document.getElementById('pdfContent').innerHTML;
        console.log(content); // Vérifie le contenu récupéré

        document.getElementById('htmlContent').value = content;
    });
});