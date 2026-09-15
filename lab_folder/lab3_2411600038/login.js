document.addEventListener("DOMContentLoaded", function () {

    const loginForm =
        document.getElementById("loginForm");

    const usernameInput =
        document.getElementById("username");

    const passwordInput =
        document.getElementById("password");

    const loginAlert =
        document.getElementById("loginAlert");

    const alertMessage =
        document.getElementById("alertMessage");

    const togglePassword =
        document.getElementById("togglePassword");


    loginForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const username =
            usernameInput.value.trim();

        const password =
            passwordInput.value.trim();

        const correctUsername = "admin";
        const correctPassword = "password123";


        if (
            username === correctUsername &&
            password === correctPassword
        ) {

            localStorage.setItem(
                "studentLoggedIn",
                "true"
            );

            localStorage.setItem(
                "studentUsername",
                username
            );


            window.location.replace("./dashboard.html");

        } else {

            if (alertMessage) {
                alertMessage.textContent =
                    "Wrong username or password.";
            }

            if (loginAlert) {
                loginAlert.classList.remove("d-none");
            }

            passwordInput.value = "";
            passwordInput.focus();

        }

    });


    if (togglePassword) {

        togglePassword.addEventListener("click", function () {

            if (passwordInput.type === "password") {

                passwordInput.type = "text";

                togglePassword.innerHTML =
                    '<i class="bi bi-eye-slash"></i>';

            } else {

                passwordInput.type = "password";

                togglePassword.innerHTML =
                    '<i class="bi bi-eye"></i>';

            }

        });

    }

});