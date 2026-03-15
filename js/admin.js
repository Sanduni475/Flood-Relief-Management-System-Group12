async function loadAdminDashboard() {
    try {
        // fetch the stats numbers from backend
        const res = await fetch(API_URL + "get_stats.php");
        // convert to json
        const json = await res.json();

        if (json.status === "success") {
            document.getElementById("stat-total-users").innerText = json.data.total_users;
            document.getElementById("stat-high-severity").innerText = json.data.high_severity;
            document.getElementById("stat-total-requests").innerText = json.data.total_requests;

            // get the table body element
            const tbody = document.getElementById("admin-recent-requests-body");
            tbody.innerHTML = ""; // clear any old data
            if (json.data.recent_requests.length > 0) {
                // loop through requests and add them to table
                json.data.recent_requests.forEach(req => {
                    // create a new row
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
            <td>#REQ-${req.Relief_ID}</td>
            <td>${req.District}</td>
            <td><span class="badge ${getSeverityBadge(req.Flood_severity_level)}">${req.Flood_severity_level}</span></td>
            <td>Pending</td>
            <td class="text-muted">${new Date(req.Created_date_time).toLocaleDateString()}</td>
          `;
                    tbody.appendChild(tr);
                });
            }
        }
    } catch (error) {
        console.error("Error loading admin stats:", error);
    }
}

async function loadUsers() {
    try {
        // fetch all users from database
        const res = await fetch(API_URL + "get_all_users.php");
        const json = await res.json();

        // get the table
        const tbody = document.getElementById("admin-users-body");
        tbody.innerHTML = ""; // clear it first

        if (json.status === "success") {
            json.data.forEach(user => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
          <td>${user.First_name} ${user.Last_name}</td>
          <td>${user.User_email}</td>
          <td><span class="badge bg-primary">Affected Person</span></td>
          <td class="action-btns">
            <button onclick="viewUserDetails(${user.User_ID})" class="btn btn-info btn-sm">View</button>
            <button onclick="deleteUser(${user.User_ID})" class="btn btn-danger btn-sm">Delete</button>
          </td>
        `;
                tbody.appendChild(tr);
            });
        }
    } catch (error) {
        console.error("Error loading users:", error);
    }
}

async function deleteUser(userId) {
    // ask for confirmation before deleting
    if (!confirm("Are you sure you want to delete this user? All their requests will also be deleted.")) return;

    try {
        const res = await fetch(API_URL + "delete_user.php", {
            method: "POST",
            body: JSON.stringify({ user_id: userId }),
            headers: { "Content-Type": "application/json" }
        });
        const result = await res.json();
        if (result.status === "success") {
            alert("User deleted successfully.");
            loadUsers();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error deleting user:", error);
    }
}

async function viewUserDetails(userId) {
    try {
        // fetch details for this specific user
        const res = await fetch(API_URL + "get_user_details.php?id=" + userId);
        const json = await res.json();

        if (json.status === "success") {
            // save data to local storage so we can use it on the next page
            localStorage.setItem("viewUserData", JSON.stringify(json));
            window.location.href = "user_details.html";
        }
    } catch (error) {
        console.error(error);
    }
}

function loadUserDetails() {
    const json = JSON.parse(localStorage.getItem("viewUserData"));
    if (!json) {
        alert("No user data found.");
        window.location.href = "users.html";
        return;
    }

    const user = json.user;
    const requests = json.requests;

    const userCard = document.getElementById("user-info-card");
    userCard.innerHTML = `
    <div class="row mb-2">
      <div class="col-md-4 fw-bold">Full Name</div>
      <div class="col-md-8">${user.First_name} ${user.Last_name}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-4 fw-bold">Email</div>
      <div class="col-md-8">${user.User_email}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-4 fw-bold">Contact Number</div>
      <div class="col-md-8">${user.Contact_Number || '-'}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-4 fw-bold">Address</div>
      <div class="col-md-8">${user.Address || '-'}</div>
    </div>
  `;

    const tableBody = document.getElementById('user-details-requests-body');
    tableBody.innerHTML = "";
    requests.forEach(req => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
      <td>${req.Type}</td>
      <td>${req.Divisional_Secretariat}</td>
      <td>${req.GN_Division}</td>
      <td>${req.Number_of_family_members}</td>
      <td><span class="badge ${getSeverityBadge(req.Flood_severity_level)}">${req.Flood_severity_level}</span></td>
      <td>${req.Description}</td>
    `;
        tableBody.appendChild(tr);
    });
}

async function loadReports() {
    try {
        const area = document.getElementById('report-area-filter').value;
        const reliefType = document.getElementById('report-type-filter').value;

        let url = API_URL + "get_reports.php?";
        if (area) url += "area=" + encodeURIComponent(area) + "&";
        if (reliefType) url += "relief_type=" + encodeURIComponent(reliefType);

        const res = await fetch(url);
        const json = await res.json();
        const tbody = document.getElementById('report-table-body');
        tbody.innerHTML = "";

        if (json.status === "success" && json.data.length > 0) {
            json.data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td>${row.relief_type}</td>
          <td>${row.total_requests}</td>
          <td>${row.high_severity}</td>
        `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = "<tr><td colspan='3' class='text-center'>No data found.</td></tr>";
        }
    } catch (error) {
        console.error("Error loading reports:", error);
    }
}
