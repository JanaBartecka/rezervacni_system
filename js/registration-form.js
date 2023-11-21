// check if passwords in input password and password_check are the same

const passwordElement = document.getElementsByName('password')[0]
const passwordAgain = document.getElementsByName('password_check')[0]
const passwordConfirm=document.querySelector('.registration-form__passwordConfirm')
const buttonSubmit = document.querySelector('.registration-form__submit')

passwordAgain.addEventListener('input', ()=> {
    if (passwordElement.value === passwordAgain.value) {
        passwordConfirm.textContent = 'Zadaná hesla jsou stejná.'
        passwordConfirm.classList.add('registration-form__passwordConfirm--valid')
        passwordConfirm.classList.remove('registration-form__passwordConfirm--invalid')
        buttonSubmit.disabled = false
    } else {
        passwordConfirm.textContent = 'Zadaná hesla nejsou stejná.'
        passwordConfirm.classList.remove('registration-form__passwordConfirm--valid')
        passwordConfirm.classList.add('registration-form__passwordConfirm--invalid')
        buttonSubmit.disabled = true
    }
    
})
