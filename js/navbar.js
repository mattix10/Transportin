const toggleButton = document.querySelector('.toggle-button');
const navbarLinks = document.querySelector('.navbar-links');
const list = document.querySelectorAll('.navbar-links li');
const container = document.querySelector('nav .container');

list.forEach(li => li.addEventListener('click', (event) => {
    if(li.className == '' || li.className == 'active-li') {
      navbarLinks.classList.toggle('active')
    };
  })
  );

toggleButton.addEventListener('click', () => {
    navbarLinks.classList.toggle('active');
  })