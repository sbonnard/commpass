// Animate campaign cards on hover //

let cards = document.querySelectorAll('[data-card]');

// console.log(cards);

cards.forEach(card => {
    card.addEventListener('mouseover', function () {
        this.classList.add('card--over');
    });
});
cards.forEach(card => {
    card.addEventListener('mouseout', function () {
        this.classList.remove('card--over');
    });
});