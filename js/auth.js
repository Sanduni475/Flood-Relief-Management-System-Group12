async function handleLogin(e) {
    // stop the page from reloading
    e.preventDefault();
    const form = e.target;

    // create an empty object to store our data
    let data = {};
    // loop over all form inputs to get their values
    for (let i = 0; i < form.elements.length; i++) {
        let input = form.elements[i];
        if (input.name) {
            data[input.name] = input.value;
        }
    }

    try {
        // send a POST request to our login api
        const res = await fetch(API_URL + "login.php", {
            method: "POST",
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" },
        });

        // convert the response to json format
        const result = await res.json();

        if (result.status === "success") {
            alert("Login User Successful!");
            // save user details in local storage so they stay logged in
            localStorage.setItem("user", JSON.stringify(result.user));
            // go to dashboard
            window.location.href = "user_dashboard.html";
        } else {
            // show error if login fails
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred.");
    }
}

async function handleRegister(e) {
    // stop the page from reloading
    e.preventDefault();
    const form = e.target;

    // simple way to get all input values
    let data = {};
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].name) {
            data[form.elements[i].name] = form.elements[i].value;
        }
    }

    try {
        // call register api
        const res = await fetch(API_URL + "register.php", {
            method: "POST",
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" },
        });

        // get the json reply
        const result = await res.json();

        if (result.status === "success") {
            alert("Registration Successful! Please login.");
            // go back to login page
            window.location.href = "index.html";
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred.");
    }
}

async function handleAdminLogin(e) {
    e.preventDefault();
    const form = e.target;

    let data = {};
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].name) {
            data[form.elements[i].name] = form.elements[i].value;
        }
    }

    try {
        const res = await fetch(API_URL + "admin_login.php", {
            method: "POST",
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" },
        });
        const result = await res.json();
        if (result.status === "success") {
            alert("Admin Login Successful!");
            localStorage.setItem("admin", JSON.stringify(result.user));
            window.location.href = "admin_dashboard.html";
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred.");
    }
}

async function handleAdminRegister(e) {
    e.preventDefault();
    const form = e.target;

    // get data from the inputs
    let data = {};
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].name) {
            data[form.elements[i].name] = form.elements[i].value;
        }
    }

    try {
        const res = await fetch(API_URL + "admin_register.php", {
            method: "POST",
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" },
        });
        const result = await res.json();
        if (result.status === "success") {
            alert("Admin Registration Successful! Please login.");
            window.location.href = "admin_login.html";
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred.");
    }
}
