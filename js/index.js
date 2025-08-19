document.addEventListener("DOMContentLoaded", function () {
  const forgotPasswordLink = document.querySelector(".extra-options a"); //selecciona el enlace de "¿Olvidaste tu contraseña?"
  const resetForm = document.querySelector(".reset-password-form"); //selecciona el formulario de recuperación de contraseña
  const loginForm = document.querySelector(".login-form"); //selecciona el formulario de login

  forgotPasswordLink.addEventListener("click", function (event) {
      event.preventDefault(); // Evita la recarga de la página al hacer clic

      //ocultar el formulario de login y mostrar el de recuperación de contraseña
      loginForm.classList.add("inactive");
      resetForm.classList.remove("inactive");
      resetForm.classList.add("active");
  });
});