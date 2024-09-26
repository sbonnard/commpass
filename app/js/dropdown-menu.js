const dropdownBtn = document.getElementById('dropdown-btn');
const dropdownChild = document.getElementById('dropdown-child');

console.log(dropdownChild);

dropdownBtn.addEventListener('click', function () {
    dropdownChild.classList.toggle('dropdown__child--active');
});
