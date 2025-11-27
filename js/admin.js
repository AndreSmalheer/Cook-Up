const sidebarNav = document.querySelectorAll(".sidebar-nav ul li");
const userSection = document.getElementById('users-section');
const recipeSection = document.getElementById('recipes-section');

const usersTable = document.getElementById('usersTable');
const recipeTable = document.getElementById('recipesTable');
const headerTable = document.getElementById('headerTable');

sidebarNav.forEach((li) => {
   li.addEventListener("click", (event) => {
        const target = li.getAttribute('id');

        if (target === 'users-section') {
            recipeSection.classList.remove('active');
            userSection.classList.add('active');

            recipeTable.style.display = 'none';
            usersTable.style.display = 'block'

            headerTable.innerHTML = 'Gebruikers'
            // let title = document.createElement('h1');
            // title.textContent = 'Test';
            // headerTable.append(title);

        }
        if (target === 'recipes-section') {
            userSection.classList.remove('active');
            recipeSection.classList.add('active');

            usersTable.style.display = 'none';
            recipeTable.style.display = 'block';

            headerTable.innerHTML = 'Recepeten';

        }
   });
});
