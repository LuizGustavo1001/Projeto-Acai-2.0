// Close Notification PopUp
document.addEventListener("DOMContentLoaded", function() {
    var popUpButton = document.querySelector(".popup-button");
    if (popUpButton) {
        popUpButton.addEventListener("click", () => {
            var popUpBox = document.querySelector(".popup-box");
            if (popUpBox) {
                popUpBox.classList.remove("show");
                popUpBox.classList.add("hidden-box");
            }
        });
    }
});