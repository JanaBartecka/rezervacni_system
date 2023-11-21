// hamburger menu
const hamburger = document.querySelector('.menu__hamburger')
const hamburgerItems = document.querySelectorAll('.menu__hamburger--item')
const menuList = document.querySelector('.menu__list')

hamburger.addEventListener('click' , () => {
    if (hamburger.classList.contains('menu__hamburger--active')) {
        hamburger.classList.remove('menu__hamburger--active')
        hamburger.classList.add('menu__hamburger--opened')
        menuList.classList.add('menu__list--opened')
        menuList.classList.remove('menu__list--closed')
        document.querySelector('main').style.display = 'none'
    } else if (hamburger.classList.contains('menu__hamburger--opened')) {
        hamburger.classList.add('menu__hamburger--active')
        hamburger.classList.remove('menu__hamburger--opened')
        menuList.classList.remove('menu__list--opened')
        menuList.classList.add('menu__list--closed')
        document.querySelector('main').style.display = 'block'
    }
})

// Log-In icon - open log in form
const loginIcon = document.querySelector('.login__icon')
const loginSection = document.querySelector('.login__section')

loginIcon.addEventListener('click', () => {
    if(loginIcon.classList.contains('login__icon--closed')) {
        loginIcon.classList.remove('login__icon--closed')
        loginIcon.classList.add('login__icon--opened')
        loginSection.classList.toggle('login__section--closed')
        document.querySelector('main').style.display = 'none'
    } else if(loginIcon.classList.contains('login__icon--opened')) {
        loginIcon.classList.remove('login__icon--opened')
        loginIcon.classList.add('login__icon--closed')
        loginSection.classList.toggle('login__section--closed')
        document.querySelector('main').style.display = 'block'
    }
})

// log-in icon - open users details
const loginName = document.querySelector('.login_userName')

loginName.addEventListener('click', ()=> {
    
})
