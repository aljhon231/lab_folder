document.addEventListener("DOMContentLoaded", function () {

    const loggedIn = localStorage.getItem("studentLoggedIn");

    if (loggedIn !== "true") {
        window.location.href = "indext.html";
        return;
    }

    const username =
        localStorage.getItem("studentUsername") || "Student";

    const navUsername =
        document.getElementById("navUsername");

    const sidebarUsername =
        document.getElementById("sidebarUsername");

    if (navUsername) {
        navUsername.textContent = username;
    }

    if (sidebarUsername) {
        sidebarUsername.textContent = username;
    }

    const greeting = "Welcome Back";

    const greetingElement =
        document.getElementById("greeting");

    if (greetingElement) {
        greetingElement.textContent =
            greeting + ", " + username + "!";
    }

    const currentDate =
        document.getElementById("currentDate");

    if (currentDate) {

        currentDate.textContent =
            new Date().toLocaleDateString(
                "en-US",
                {
                    weekday: "long",
                    year: "numeric",
                    month: "long",
                    day: "numeric"
                }
            );
    }


    const activities = [

        {
            activity: "Submitted Laboratory Exercise 2",
            course: "Web Systems",
            date: "August 15, 2026",
            status: "Completed"
        },

        {
            activity: "Submitted Assignment 3",
            course: "Database Systems",
            date: "August 14, 2026",
            status: "Completed"
        },

        {
            activity: "Quiz 2",
            course: "Programming",
            date: "August 13, 2026",
            status: "Completed"
        },

        {
            activity: "Laboratory Exercise 3",
            course: "Web Systems",
            date: "August 18, 2026",
            status: "Pending"
        }

    ];


    const activityTable =
        document.getElementById("activityTable");

    if (activityTable) {

        activityTable.innerHTML = "";

        activities.forEach(function (item) {

            const row =
                document.createElement("tr");

            const statusClass =
                item.status === "Completed"
                    ? "bg-success-subtle text-success"
                    : "bg-warning-subtle text-warning-emphasis";

            row.innerHTML = `
                <td>
                    <i class="bi bi-file-earmark-text me-2"></i>
                    ${item.activity}
                </td>

                <td>
                    ${item.course}
                </td>

                <td>
                    ${item.date}
                </td>

                <td>
                    <span class="status-badge ${statusClass}">
                        ${item.status}
                    </span>
                </td>
            `;

            activityTable.appendChild(row);

        });

    }


    const logoutBtn =
        document.getElementById("logoutBtn");

    if (logoutBtn) {

        logoutBtn.addEventListener("click", function () {

            // Remove login session
            localStorage.removeItem("studentLoggedIn");
            localStorage.removeItem("studentUsername");

            // Return to login page
            window.location.href = "indext.html";

        });

    }

});