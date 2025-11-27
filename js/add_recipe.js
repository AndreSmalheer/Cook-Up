// INGREDIENTS
const ingredientsWrapper = document.getElementById("ingredients-wrapper");
const addIngredientBtn = document.getElementById("add-ingredient-btn");

addIngredientBtn.addEventListener("click", () => {
    const div = document.createElement("div");
    div.className = "ingredient-row";

    div.innerHTML = `
        <input type="text" name="ingredient_name[]" placeholder="Ingrediënt" required>
        <input type="text" name="ingredient_quantity[]" placeholder="Hoeveelheid">
        <button type="button" class="remove-btn">x</button>
    `;

    ingredientsWrapper.appendChild(div);

    div.querySelector(".remove-btn").addEventListener("click", () => {
        div.remove();
    });
});

// STEPS
const stepsWrapper = document.getElementById("steps-wrapper");
const addStepBtn = document.getElementById("add-step-btn");

addStepBtn.addEventListener("click", () => {
    const div = document.createElement("div");
    div.className = "step-row";

    div.innerHTML = `
        <textarea name="step_instruction[]" placeholder="Beschrijving van deze stap" required></textarea>
        <button type="button" class="remove-btn">x</button>
    `;

    stepsWrapper.appendChild(div);

    div.querySelector(".remove-btn").addEventListener("click", () => {
        div.remove();
    });
});
