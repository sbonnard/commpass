// BUTTONS & FORMS


// BUTTONS
const buttonEmail = document.getElementById('button-email');
const buttonPWD = document.getElementById('button-pwd');
const buttonTel = document.getElementById('button-tel');

console.log(buttonEmail, buttonPWD, buttonTel);

//FORMS

const formEmail = document.getElementById('email_form');
const formPWD = document.getElementById('pwd_form');
const formTel = document.getElementById('phone_form');

console.log(formEmail, formPWD, formTel);

// LISTENERS

// email
buttonEmail.addEventListener('click', function () {
    // EMAIL FORM AND BUTTON
    if (!buttonEmail.classList.contains('profil__lnk--active')) {
        buttonEmail.classList.toggle('profil__lnk--active');
        formEmail.classList.toggle('hidden');

        // PASSWORD FORM AND BUTTON
        buttonPWD.classList.remove('profil__lnk--active');
        formPWD.classList.add('hidden');

        // TEL FORM AND BUTTON
        buttonTel.classList.remove('profil__lnk--active');
        formTel.classList.add('hidden');
    }
})


// PASSWORD
buttonPWD.addEventListener('click', function () {
    // PASSWORD FORM AND BUTTON
    if (!buttonPWD.classList.contains('profil__lnk--active')) {
        buttonPWD.classList.toggle('profil__lnk--active');
        formPWD.classList.toggle('hidden');

        // EMAIL FORM AND BUTTON
        buttonEmail.classList.remove('profil__lnk--active');
        formEmail.classList.add('hidden');

        // TEL FORM AND BUTTON
        buttonTel.classList.remove('profil__lnk--active');
        formTel.classList.add('hidden');
    }
})


// tel
buttonTel.addEventListener('click', function () {
    // TEL FORM AND BUTTON
    if (!buttonTel.classList.contains('profil__lnk--active')) {
        buttonTel.classList.toggle('profil__lnk--active');
        formTel.classList.toggle('hidden');

        // PASSWORD FORM AND BUTTON
        buttonPWD.classList.remove('profil__lnk--active');
        formPWD.classList.add('hidden');

        // EMAIL FORM AND BUTTON
        buttonEmail.classList.remove('profil__lnk--active');
        formEmail.classList.add('hidden');
    }
})

// COLOR FORM 

document.getElementById('profil_brand').addEventListener('change', function () {
    var selectedOption = this.options[this.selectedIndex];
    var colorHex = selectedOption.getAttribute('data-color');

    if (colorHex) {
        document.getElementById('color').value = colorHex;
    } else {
        document.getElementById('color').value = '#000000'; // Valeur par défaut si aucune couleur n'est trouvée
    }
});