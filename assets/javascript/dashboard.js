function showSection(sectionId){
    let sections = document.querySelectorAll(".section");

    sections.forEach(section => {
        section.classList.remove("active");
    });

    document.getElementById(sectionId).classList.add("active");
}


// function updateTechDisplay(){
//     const select = document.getElementById("techSelect");
//     const display = document.getElementById("techDisplay");

//     let selected = [];
//     for(let option of select.options){
//         if(option.selected){
//             selected.push(option.value);
//         }
//     }
//     display.value = selected.join(", ");
// }


const technologies = [
    "HTML","CSS","JavaScript","PHP","MySQL","Python",
    "Bootstrap","React","Node.js","Laravel","Django",
    "MongoDB","Git","jQuery"
];

let selectedTech = [];

const input = document.getElementById("techInput");
const display = document.getElementById("techDisplay");
const hidden = document.getElementById("techHidden");
const box = document.getElementById("techSuggestions");

input.addEventListener("keyup", function () {
    const val = this.value.toLowerCase();
    box.innerHTML = "";

    if (!val) return;

    technologies.forEach(t => {
        if (t.toLowerCase().includes(val) && !selectedTech.includes(t)) {
            let div = document.createElement("div");
            div.textContent = t;
            div.onclick = () => addTech(t);
            box.appendChild(div);
        }
    });
});

function addTech(tech){
    selectedTech.push(tech);
    display.value = selectedTech.join(", ");
    hidden.value = selectedTech.join(",");
    input.value = "";
    box.innerHTML = "";
}





