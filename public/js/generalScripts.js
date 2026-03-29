// Close Notification PopUp
document.addEventListener("DOMContentLoaded", function() {
  const popUpButton = document.querySelector(".popup-button");
  if (popUpButton) {
    popUpButton.addEventListener("click", () => {
      const popUpBox = document.querySelector(".popup-box");
      popUpBox.classList.remove("show");
      if (popUpBox) popUpBox.classList.add("hidden-box");
    });
  }
});