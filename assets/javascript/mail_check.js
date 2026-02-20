document.addEventListener("DOMContentLoaded", function () {

    var emailValid = true;   // ✅ start as true
    var emailChecked = false; // ✅ track if ajax checked
    var emailInput = document.getElementById("email");
    var emailError = document.getElementById("emailError");
    var form = document.querySelector("form");

    emailInput.addEventListener("keyup", function () {

        var email = emailInput.value.trim();

        if (email.length === 0) {
            emailError.innerHTML = "";
            emailValid = false;
            emailChecked = false;
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax/check_email.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function () {

            emailChecked = true;

            if (xhr.responseText.trim() === "exists") {
                emailError.innerHTML =
                    "This email already exists. <a href='login.php'>Login</a>";
                emailValid = false;
            } else {
                emailError.innerHTML = "";
                emailValid = true;
            }
        };

        xhr.send("email=" + encodeURIComponent(email));
    });

    form.addEventListener("submit", function (e) {

        // If email field is empty
        if (emailInput.value.trim() === "") {
            emailError.innerHTML = "Email is required";
            e.preventDefault();
            return;
        }

        // If AJAX hasn't checked yet
        if (!emailChecked) {
            e.preventDefault();
            return;
        }

        // If email exists
        if (!emailValid) {
            emailError.innerHTML =
                "This email already exists. <a href='login.php'>Login</a>";
            e.preventDefault();
        }
    });

});

console.log("Mail check js loaded");