// FORM
const form = document.querySelector('form');

// INPUTS
const nom = form.elements.namedItem('nom'),
  prenom = form.elements.namedItem('prenom'),
  date_de_naissance = form.elements.namedItem('date_de_naissance');

// BUTTONS
const btnAfficher = form.elements.namedItem('btnAfficher'),
  btnAnnuler = form.elements.namedItem('btnAnnuler'),
  btnSubmit = form.elements.namedItem("envoyer")

/*
  FUNCTION validate POUR VALIDER LE CONTENU DES INPUTS POUR CHANQUE CHANGEMENT "input"

  Veuillez remplir tous les champs (texte/date), la saisie n'accepte pas les vides, un seul caractère ou des chiffres.
*/
function validate(e) {
  if (e.target.name === "nom") {
    if (empty(e.target.value) || is_text(e.target.value) || e.target.value.length === 1) {
      e.target.parentElement.classList.add('invalid');
      e.target.parentElement.classList.remove('valid');
    } else {
      e.target.parentElement.classList.add('valid');
      e.target.parentElement.classList.remove('invalid');
    }
  }

  if (e.target.name === "prenom") {
    if (empty(e.target.value) || is_text(e.target.value) || e.target.value.length === 1) {
      e.target.parentElement.classList.add('invalid');
      e.target.parentElement.classList.remove('valid');
    } else {
      e.target.parentElement.classList.add('valid');
      e.target.parentElement.classList.remove('invalid');
    }
  }

  if (e.target.name === "date_de_naissance") {
    if (is_date(e.target.value)) {
      e.target.parentElement.classList.add('invalid');
      e.target.parentElement.classList.remove('valid');
    } else {
      e.target.parentElement.classList.add('valid');
      e.target.parentElement.classList.remove('invalid');
    }
  }
}

nom.addEventListener('input', validate);
prenom.addEventListener('input', validate);
date_de_naissance.addEventListener('input', validate);
const spanAlert = document.querySelector('.span-alert');

form.addEventListener('input', () => {
  let invalid = document.querySelector('.invalid');
  if (!invalid) {
    btnAfficher.disabled = false;
    btnSubmit.disabled = false;
    spanAlert.classList.remove('invalid-span');
  } else {
    spanAlert.classList.add('invalid-span');
    btnAfficher.disabled = true;
    btnSubmit.disabled = true;
  }
})

// FUNCTIONS
const calcAge = birthday => new Date().getFullYear() - birthday.getFullYear();
const empty = string => !string.trim().length;
const is_text = string => !/^[a-zA-Z ]*$/.test(string);
const is_date = date => !/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/.test(date);


// EVENTS
btnAfficher.addEventListener('click', (event) => {
  event.preventDefault();

  const age = calcAge(new Date(date_de_naissance.value));

  // EXEMPLE : ucfirst_prenom('adil') => 'Adil'
  const ucfirst_prenom = prenom.value.charAt(0).toUpperCase() + prenom.value.slice(1);

  const today = new Date();
  const todayMonth = today.getUTCMonth() + 1;
  const todayDay = today.getUTCDate();

  const birthday = new Date(date_de_naissance.value);
  const birthdayMonth = birthday.getUTCMonth() + 1;
  const birthdayDay = birthday.getUTCDate();

  if (age === 0) {
    // EVITER D'AFFICHER "vous avez 0 ans"
    alert(`Joyeux anniversaire! ${ucfirst_prenom} 🎉`);
  } else if (todayDay === birthdayDay && todayMonth === birthdayMonth) {
    // DIRE QUELQUE CHOSE DE GENTIL
    alert(`Joyeux anniversaire! ${ucfirst_prenom} 🎉, vous avez ${age} ans`);
  } else {
    // QUESTION TP DEMANDE
    alert(`Bonjour ${ucfirst_prenom}, vous avez ${age} ans`);
  }

  btnSubmit.disabled = false;
})

btnAnnuler.addEventListener('click', (event) => {
  // REINITIALISER LE FORMULAIRE
  event.preventDefault();

  form.reset();
  nom.focus();

  nom.parentElement.classList.remove('valid');
  nom.parentElement.classList.add('invalid');
  prenom.parentElement.classList.remove('valid');
  prenom.parentElement.classList.add('invalid');
  date_de_naissance.parentElement.classList.remove('valid');
  date_de_naissance.parentElement.classList.add('invalid');

  btnAfficher.disabled = true;
  btnSubmit.disabled = true;
})

// CRÈME
nom.focus();
date_de_naissance.setAttribute('max', new Date().toISOString().slice(0, 10)); // DEFINIR UNE DATE MAX
btnAfficher.disabled = true;
btnSubmit.disabled = true;




