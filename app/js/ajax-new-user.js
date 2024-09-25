document.getElementById('company').addEventListener('change', function() {
    var selectedCompany = this.options[this.selectedIndex].text;
    var inputHidden = document.getElementById('status');

    if (selectedCompany === 'Toile de Com') {
        inputHidden.value = 0;
    } else {
        inputHidden.value = 1;
    }
});