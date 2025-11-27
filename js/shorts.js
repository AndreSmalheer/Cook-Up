const shorts = document.querySelectorAll(".short");
let current = 0;
let canScroll = true;

const SCROLL_THRESHOLD = 30;
const COOLDOWN = 700;

// vult de bestaande HTML van de info-box
function loadRecipeInfo(index) {
    const recipe = ITEMS[index]; // haal juiste recept uit PHP data

    if (!recipe) return;

    const recepyBox = document.getElementById("recepy");
    if (!recepyBox) return;

    // bouw de bestaande layout opnieuw maar met juiste data
    let html = `
        <h2>${recipe.title}</h2>
        <p>${recipe.description}</p>

        <h3>Ingredients</h3>
        <ul>
    `;

    recipe.ingredients.forEach(ing => {
        html += `<li>${ing}</li>`;
    });

    html += "</ul>";

    if (recipe.steps.length > 0) {
        html += "<h3>Steps</h3><ol>";
        recipe.steps.forEach(step => {
            html += `
                <li>
                    <strong>Step ${step.stepNumber}:</strong>
                    ${step.instructions}
                </li>
            `;
        });
        html += "</ol>";
    }

    recepyBox.innerHTML = html;

    // update URL
    window.history.replaceState({}, "", `?recept_id=${recipe.id}`);
}

// short wisselen
function showShort(next, direction) {
    if (current === next) return;

    shorts[current].classList.remove("active");
    shorts[next].classList.add("active");

    current = next;

    // Update info rechts met juiste recept
    loadRecipeInfo(current);
}

// scroll naar volgende
function nextShort() {
    if (!canScroll) return;
    canScroll = false;

    let next = current + 1;
    if (next >= shorts.length) next = 0;

    showShort(next, "down");

    setTimeout(() => { canScroll = true; }, COOLDOWN);
}

// scroll naar vorige
function prevShort() {
    if (!canScroll) return;
    canScroll = false;

    let next = current - 1;
    if (next < 0) next = shorts.length - 1;

    showShort(next, "up");

    setTimeout(() => { canScroll = true; }, COOLDOWN);
}

// scroll control
function onWheel(e) {
    e.preventDefault();
    if (!canScroll) return;

    const delta = e.deltaY;
    if (Math.abs(delta) < SCROLL_THRESHOLD) return;

    delta > 0 ? nextShort() : prevShort();
}

window.addEventListener("wheel", onWheel, { passive: false });

// touch support
let touchStartY = null;

window.addEventListener("touchstart", e => {
    touchStartY = e.touches[0].clientY;
}, { passive: true });

window.addEventListener("touchmove", e => {
    if (touchStartY === null) return;
    const dy = touchStartY - e.touches[0].clientY;
    if (!canScroll) return;

    if (Math.abs(dy) > 50) {
        e.preventDefault();
        dy > 0 ? nextShort() : prevShort();
        touchStartY = null;
    }
}, { passive: false });

// hartje animatie
function heart() {
    const container = document.getElementById("favroute");
    const img = container.querySelector("img");

    if (img.src.includes("unfilled")) {
        img.src = "images/heart.png";
    } else {
        img.src = "images/heart_unfilled.png";
    }

    img.classList.add("animated");
    img.addEventListener("animationend", () => {
        img.classList.remove("animated");
    }, { once: true });
}

// info window toggle
function show_recepy_window() {
    document.getElementById("short_container").style.display = "none";
    document.getElementById("recepy").style.display = "block";
}

function hide_show_recepy_window() {
    document.getElementById("recepy").style.display = "none";
    document.getElementById("short_container").style.display = "block";
}

function toggle_recepy_window() {
    const box = document.getElementById("recepy");
    if (box.style.display === "block") hide_show_recepy_window();
    else show_recepy_window();
}

// start met het eerste recept
loadRecipeInfo(0);
