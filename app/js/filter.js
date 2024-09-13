// FILTER

const filterButton = document.getElementById('filter-button');
const filterForm = document.getElementById('filter-form');
const filterContainer = document.getElementById('filter-container');

// console.log(filterButton);
// console.log(filterForm);

filterButton.addEventListener('click', function () {
    filterForm.classList.toggle('hidden');
    filterContainer.classList.toggle('hidden');
});
