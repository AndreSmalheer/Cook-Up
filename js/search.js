export function search(parent_container, search_container, search_entry, elementTitle) {
    console.log("test")
    const items = parent_container.querySelectorAll(search_container);
    if (!items) return;

    const search_entry_lower = search_entry.toLowerCase();

    for (const item of items) {
        let titleEl = item.querySelector(elementTitle);
        if (!titleEl) continue;

        const textToCheck = titleEl.textContent.toLowerCase();

        if (!search_entry_lower) {
            item.style.display = "block"; 
            continue; 
        }

        if (!textToCheck.includes(search_entry_lower)) {
            item.style.display = "none"; 
        } else {
            item.style.display = "block";
        }
    }

    parent_container.scrollTop = 0;
    parent_container.scrollLeft = 0;
}



const catogory_search = document.getElementById("catogory_search_input");
const trending_search = document.getElementById("trending_search_input");

if(trending_search){
trending_search.addEventListener("input", function() {
    const searchText = trending_search.value;
    search(document.getElementById("trending_items"), ".category-item", searchText, ".category-title");
});
}

console.log(catogory_search)
if(catogory_search){
catogory_search.addEventListener("input", function() {
    const searchText = catogory_search.value;
    search(document.getElementById("catogory_items"), ".category-item", searchText, ".category-title");
});
}

