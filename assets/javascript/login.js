const form = document.getElementById("loginForm");
const email = document.getElementById("email");
const password = document.getElementById("password");

const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");

const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

email.addEventListener("keyup", validateEmail);
password.addEventListener("keyup", validatePassword);

function validateEmail() {
    if (email.value.trim() === "") {
        emailError.textContent = "Email is required";
        email.classList.add("error-border");
        return false;
    }
    else if (!email.value.match(emailPattern)) {
        emailError.textContent = "Enter a valid email address";
        email.classList.add("error-border");
        return false;
    }
    else {
        emailError.textContent = "";
        email.classList.remove("error-border");
        return true;
    }
}

function validatePassword() {
    if (password.value.trim() === "") {
        passwordError.textContent = "Password is required";
        password.classList.add("error-border");
        return false;
    }
    else if (password.value.length < 6) {
        passwordError.textContent = "Minimum 6 characters required";
        password.classList.add("error-border");
        return false;
    }
    else {
        passwordError.textContent = "";
        password.classList.remove("error-border");
        return true;
    }
}

form.addEventListener("submit", function(e){
    if(!validateEmail() || !validatePassword()){
        e.preventDefault();
    }
});
