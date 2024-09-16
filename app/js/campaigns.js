// Animate campaign cards on hover //

let campaignsCards = document.querySelectorAll('[data-campaign]');

// console.log(campaignsCards);

campaignsCards.forEach(card => {
    card.addEventListener('mouseover', function() {
        this.classList.add('card--over');
    });
});
campaignsCards.forEach(card => {
    card.addEventListener('mouseout', function() {
        this.classList.remove('card--over');
    });
});