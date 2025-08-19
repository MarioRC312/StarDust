document.addEventListener("DOMContentLoaded", function () 
{

    const firstPassword = document.getElementById("password");
    const scndPassword = document.getElementById("veri-pswd");

    function validatePasswords() {
      
        const match = firstPassword.value.startsWith(scndPassword.value);
       
        scndPassword.classList.remove("error-border", "correct-border");
        if (!match) 
        {
            scndPassword.classList.add("error-border");
            shakeAnimation(scndPassword);
        }
       
        if (firstPassword.value === scndPassword.value) 
        {
            scndPassword.classList.add("correct-border");
        }
        
    }

    function shakeAnimation(element) {
        element.style.animation = "shake 0.3s ease-in-out";
        setTimeout(() => {
            element.style.animation = "";
        }, 300);
    }

    scndPassword.addEventListener("input", validatePasswords);
});