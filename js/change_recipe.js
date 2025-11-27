// ingredienten toevoegen
document.getElementById("add-ingredient-btn").addEventListener("click", function () {
    const wrapper = document.getElementById("ingredients-wrapper");

    const row = document.createElement("div");
    row.classList.add("ingredient-row");

    row.innerHTML = `
        <input type="text" name="ingredients[new_${Date.now()}][name]" placeholder="Ingrediënt naam" required>
        <input type="text" name="ingredients[new_${Date.now()}][quantity]" placeholder="Hoeveelheid">

        <button type="button" class="delete-btn">Verwijderen</button>
    `;

    wrapper.appendChild(row);
});

// stap toevoegene
document.getElementById("add-step-btn").addEventListener("click", function () {
    const wrapper = document.getElementById("steps-wrapper");

    const row = document.createElement("div");
    row.classList.add("step-row");

    row.innerHTML = `
        <textarea name="steps[new_${Date.now()}][instruction]" placeholder="Beschrijving van de stap" required></textarea>
        <button type="button" class="delete-btn">Verwijderen</button>
    `;

    wrapper.appendChild(row);
});

// delet button
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("delete-btn")) {
        event.target.parentElement.remove();
    }
});
