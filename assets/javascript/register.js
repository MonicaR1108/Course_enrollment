function togglePassword() {
    var pass = document.getElementById("password");

    if (pass.type === "password") {
        pass.type = "text";
    } else {
        pass.type = "password";
    }
}



// const passwordInput = document.getElementById("password");

// const len = document.getElementById("len");
// const upper = document.getElementById("upper");
// const num = document.getElementById("num");
// const special = document.getElementById("special");

// passwordInput.addEventListener("keyup", function () {
//     const value = passwordInput.value;

//     // Length
//     if (value.length >= 8) {
//         len.style.color = "green";
//     } else {
//         len.style.color = "red";
//     }

//     // Uppercase
//     if (/[A-Z]/.test(value)) {
//         upper.style.color = "green";
//     } else {
//         upper.style.color = "red";
//     }

//     // Number
//     if (/[0-9]/.test(value)) {
//         num.style.color = "green";
//     } else {
//         num.style.color = "red";
//     }

//     // Special character
//     if (/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
//         special.style.color = "green";
//     } else {
//         special.style.color = "red";
//     }
// });

// // Prevent submit if password invalid
// document.querySelector("form").addEventListener("submit", function (e) {
//     const value = passwordInput.value;

//     if (
//         value.length < 8 ||
//         !/[A-Z]/.test(value) ||
//         !/[0-9]/.test(value) ||
//         !/[!@#$%^&*(),.?":{}|<>]/.test(value)
//     ) {
//         alert("Password does not meet the required conditions");
//         e.preventDefault();
//     }
// });


document.getElementById("registerForm").addEventListener("submit", function (e) {

    const password = document.getElementById("password").value;

    const hasLength  = password.length >= 8;
    const hasUpper   = /[A-Z]/.test(password);
    const hasNumber  = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    if (!(hasLength && hasUpper && hasNumber && hasSpecial)) {
        e.preventDefault();   // 🔴 THIS WAS THE MISSING GUARANTEE
        alert(
            "Password must contain:\n" +
            "- Minimum 8 characters\n" +
            "- One uppercase letter\n" +
            "- One number\n" +
            "- One special character"
        );
        return false;
    }

});