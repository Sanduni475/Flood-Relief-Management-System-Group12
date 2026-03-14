function loadUserDashboard() {
    const user = JSON.parse(localStorage.getItem("user"));
    if (user) {
        document.getElementById("welcome-name").innerText = `Welcome Back, ${user.name}! 👋`;
    }
}

async function handleSubmitRequest(e) {
    // stop full page reload
    e.preventDefault();
    const form = e.target;

    // manually collect all form data into an object
    let data = {};
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].name) {
            data[form.elements[i].name] = form.elements[i].value;
        }
    }

    try {
        // send the data to backend
        const res = await fetch(API_URL + "submit_request.php", {
            method: "POST",
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" },
        });
        const result = await res.json();

        if (result.status === "success") {
            alert("Request Submitted Successfully!");
            // clear the form after success
            form.reset();
            // redirect to my requests page
            window.location.href = "my_requests.html";
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

async function loadMyRequests() {
    try {
        // get data from the api
        const res = await fetch(API_URL + "get_my_requests.php");
        const json = await res.json();

        // find the table body where we will put the rows
        const tbody = document.getElementById("my-requests-body");
        tbody.innerHTML = ""; // clear old data

        if (json.status === "success" && json.data.length > 0) {
            // loop through all the requests we got from the server
            json.data.forEach(req => {
                // create a new table row for each request
                const tr = document.createElement("tr");
                const severity = req.Flood_severity_level || req.Severity || 'Unknown';
                tr.innerHTML = `
          <td>${req.Type}</td>
          <td>${req.District}</td>
          <td><span class="badge ${getSeverityBadge(severity)}">${severity}</span></td>
          <td>
            <button onclick="editRequest(${req.Relief_ID})" class="btn btn-warning btn-sm">Edit</button>
            <button onclick="deleteRequest(${req.Relief_ID})" class="btn btn-danger btn-sm">Delete</button>
          </td>
        `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = "<tr><td colspan='4' class='text-center'>No requests found.</td></tr>";
        }
    } catch (error) {
        console.error("Error loading requests:", error);
    }
}

async function deleteRequest(id) {
    if (!confirm("Are you sure you want to delete this request?")) return;

    try {
        const res = await fetch(API_URL + "delete_request.php", {
            method: "POST",
            body: JSON.stringify({ relief_id: id }),
            headers: { "Content-Type": "application/json" }
        });
        const result = await res.json();
        if (result.status === "success") {
            alert("Request deleted.");
            loadMyRequests();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

async function editRequest(id) {
    try {
        const res = await fetch(API_URL + "get_request.php?id=" + id);
        const result = await res.json();

        if (result.status === "success") {
            const data = result.data;
            localStorage.setItem("editRequestData", JSON.stringify(data));
            window.location.href = "edit_request.html";
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

function loadEditForm() {
    const data = JSON.parse(localStorage.getItem("editRequestData"));
    if (!data) {
        alert("No request data found.");
        window.location.href = "my_requests.html";
        return;
    }

    document.getElementById('edit-relief-id').value = data.Relief_ID;
    document.getElementById('edit-type').value = data.Type;
    document.getElementById('edit-district').value = data.District;
    document.getElementById('edit-div-sec').value = data.Divisional_Secretariat;
    document.getElementById('edit-gn-div').value = data.GN_Division;
    document.getElementById('edit-family-members').value = data.Number_of_family_members;
    document.getElementById('edit-severity').value = data.Flood_severity_level;
    document.getElementById('edit-description').value = data.Description;

    fetch(API_URL + "get_profile.php")
        .then(res => res.json())
        .then(userJson => {
            if (userJson.status === "success") {
                document.getElementById('edit-contact-person').value = userJson.data.First_name + " " + userJson.data.Last_name;
                document.getElementById('edit-contact-number').value = userJson.data.Contact_Number || "";
                document.getElementById('edit-address').value = userJson.data.Address || "";
            }
        });
}

async function handleUpdateRequest(e) {
    e.preventDefault();
    const form = e.target;

    // get form data using a loop
    let data = {};
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].name) {
            data[form.elements[i].name] = form.elements[i].value;
        }
    }

    try {
        const res = await fetch(API_URL + "update_request.php", {
            method: "POST",
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" }
        });
        const result = await res.json();
        if (result.status === "success") {
            alert("Request Updated Successfully!");
            localStorage.removeItem("editRequestData");
            window.location.href = "my_requests.html";
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
    }
}
