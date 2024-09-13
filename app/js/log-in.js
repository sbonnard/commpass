// Connection Menu //

const ConnectionLink = document.getElementById('connection-menu');

const overlayConnection = document.getElementById('connection-form');

ConnectionLink.addEventListener('click', function () {
    this.classList.toggle("close");
    overlay.classList.remove("overlay");
    overlayConnection.classList.toggle("overlay");
});