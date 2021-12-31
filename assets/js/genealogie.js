// BUTTONS
const radioBtns = document.querySelectorAll('input[type=radio]');

// FORM
const formGenealogie = document.querySelector('.form-genealogie')

// COTAINERS
const familyContainer = document.querySelector('.input__container:nth-of-type(1)'),
  radioContainer = document.querySelector('.input__container:nth-of-type(2)'),
  modeContainer = document.querySelector('.input__container:nth-of-type(3)');

// SELECT TAGS
const selectFamily = document.querySelector('.family');
const selectMode = document.querySelector('.mode');

/*
  DES CONDITIONS POUR VERIFIER TOUS LES CHAMPS (containers) AFIN D'ACTIVER LE BUTTON TRIER
*/
formGenealogie.addEventListener('change', () => {
  const inputSubmit = document.querySelector("input[type=submit]")

  if (selectFamily.value !== 'empty') {
    radioContainer.classList.add('input__container-active')
  } else {
    radioContainer.classList.remove('input__container-active')
  }

  if (radioBtns[0].checked || radioBtns[1].checked) {
    modeContainer.classList.add('input__container-active')
  } else {
    modeContainer.classList.remove('input__container-active')
  }

  if (selectFamily.value === 'empty') {
    inputSubmit.disabled = true;
    radioBtns[0].checked = false;
    radioBtns[1].checked = false;
    selectMode.value = 'empty';
    modeContainer.classList.remove('input__container-active');
    console.log('TEST');
  }

  if (selectMode.value !== 'empty') {
    inputSubmit.disabled = false;
  } else {
    inputSubmit.disabled = true;
  }
})