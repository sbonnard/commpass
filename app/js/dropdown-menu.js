// GESTION DU DROPDOWN CLIENT POUR LA VERSION MOBILE 

const dropdownBtnClient = document.getElementById('dropdown-btn-client');
const dropdownChildClient = document.getElementById('dropdown-child-client');

dropdownBtnClient.addEventListener('click', function () {
    dropdownChildClient.classList.toggle('dropdown__child--active');
});

// GESTION DU DROPDOWN AGENCE POUR LA VERSION MOBILE 

const dropdownBtnAgency = document.getElementById('dropdown-btn-agency');
const dropdownChildAgency = document.getElementById('dropdown-child-agency');

dropdownBtnAgency.addEventListener('click', function () {
    dropdownChildAgency.classList.toggle('dropdown__child--active');
});