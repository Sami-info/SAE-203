const main = document.querySelector("main");

main.style.opacity = "0";
main.style.transform = "translateY(40px)";
main.style.transition = "opacity 0.8s ease, transform 0.8s ease";

window.addEventListener("load", function () {
  main.style.opacity = "1";
  main.style.transform = "translateY(0)";
});

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");
const eyeIcon = document.getElementById("eyeIcon");

togglePassword.addEventListener("click", function () {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    eyeIcon.classList.remove("fa-eye");
    eyeIcon.classList.add("fa-eye-slash");
  } else {
    passwordInput.type = "password";
    eyeIcon.classList.remove("fa-eye-slash");
    eyeIcon.classList.add("fa-eye");
  }
});
