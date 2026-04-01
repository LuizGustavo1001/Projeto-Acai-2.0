const body = document.body


// toggle theme + last localStorage theme 
const toggleThemeBox = document.querySelectorAll(".toggle-theme")
toggleThemeBox.forEach(box => {
    box.addEventListener("click", toggleTheme)
})

function toggleTheme(){
    const selectedBg = document.querySelectorAll(".toggle-theme .selected-bg")
    selectedBg.forEach(item => {
        item.classList.toggle("outer")
    })

    const iconBg = document.querySelectorAll(".toggle-theme .icon-bg")
    iconBg.forEach(icon => {
        icon.classList.toggle("active")
    })

    body.classList.toggle("dark-mode")

    const isDark = body.classList.contains("dark-mode")
    localStorage.setItem("theme", isDark ? "dark" : "light")

}

const lastTheme = localStorage.getItem("theme")
if(lastTheme == "dark"){
    toggleTheme()
}

// Mobile sidebar toggle
const toggleSidebarButton = document.querySelectorAll(".icon-button.menu")
const dazzlesBg = document.querySelector(".dazzles-bg")
const cardNav = document.querySelector("header nav")

document.addEventListener("click", (e) => {
    const clickedInsideNav = e.target.closest("header nav")
    const clickedToggle = e.target.closest(".icon-button.menu")

    if(! clickedInsideNav && !clickedToggle){
        if(cardNav.classList.contains("open")){
            toggleSidebar()
        }
    }
})

toggleSidebarButton.forEach(button => {
    button.addEventListener("click", toggleSidebar)
})

function toggleSidebar(){
    dazzlesBg.classList.toggle("open")
    cardNav.classList.toggle("open")
}



/* toggle warning display */
const warningBox = document.querySelector(".warning")
if (warningBox) {
    const removeBox = () => {
        warningBox.classList.add("fade-out")
        setTimeout(() => warningBox.remove(), 500)
    }

    warningBox.addEventListener("click", removeBox)

    setTimeout(removeBox, 10000)
}