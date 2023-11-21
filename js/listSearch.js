const AllFirstName = Array.from(document.querySelectorAll('.list__item--firstName'))
const AllSecondName = Array.from(document.querySelectorAll('.list__item--secondName'))
const AllPhone = Array.from(document.querySelectorAll('.list__item--phone'))
const AllEmail = Array.from(document.querySelectorAll('.list__item--email'))

const inputField = document.getElementsByName('list__search')[0]
const List = document.querySelector('.list')

// create array of all users, each user is an object with first name, second name, phone and email
let UserDetailsObjects = []
for(i = 0; i <= AllFirstName.length - 1; i++) {
    UserDetailsObjects[i] =  {
        'firstName':AllFirstName[i].textContent,
        'secondName':AllSecondName[i].textContent,
        'phone':AllPhone[i].textContent,
        'email':AllEmail[i].textContent
    }
}

// add event listener to input field
inputField.addEventListener('input',()=> {
    // get text from input field
    const inputText = inputField.value.toLowerCase()
    // delete content of 'ul' element
    List.textContent = ''

    // filter array UserDetailsObjects by text written into input field
    const filteredUserInfo = UserDetailsObjects.filter((oneUser) => {
        return (oneUser.firstName.toLowerCase().includes(inputText) || oneUser.secondName.toLowerCase().includes(inputText) || oneUser.phone.toLowerCase().includes(inputText) || oneUser.email.toLowerCase().includes(inputText))
    })

    // create new content of 'li' element
    filteredUserInfo.map ( (oneUser) => {
        const newLiElement = document.createElement('li')
        newLiElement.classList.add('list__item')

        const spanFirstName = document.createElement('span')
        spanFirstName.classList.add('list__item--firstName')
        spanFirstName.textContent = oneUser.firstName
        newLiElement.append(spanFirstName)

        const spanSecondName = document.createElement('span')
        spanSecondName.classList.add('list__item--firstName')
        spanSecondName.textContent = oneUser.secondName
        newLiElement.append(spanSecondName)

        const spanPhone = document.createElement('span')
        spanPhone.classList.add('list__item--firstName')
        spanPhone.textContent = oneUser.phone
        newLiElement.append(spanPhone)

        const spanEmail = document.createElement('span')
        spanEmail.classList.add('list__item--firstName')
        spanEmail.textContent = oneUser.email
        newLiElement.append(spanEmail)

        List.append(newLiElement)

    })

})