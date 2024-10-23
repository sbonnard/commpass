const dropdownBtn = document.getElementById('dropdown-btn');
const dropdownChild = document.getElementById('dropdown-child');

dropdownBtn.addEventListener('click', function () {
    dropdownChild.classList.toggle('dropdown__child--active');
});


const dropdownBtnAgency = document.getElementById('dropdown-btn-agency');
const dropdownChildAgency = document.getElementById('dropdown-child-agency');

dropdownBtnAgency.addEventListener('click', function () {
    dropdownChildAgency.classList.toggle('dropdown__child--active');
});