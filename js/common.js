const API_URL = "api/";

function getSeverityBadge(severity) {
    if (!severity) return "bg-secondary";
    if (severity === "High") return "bg-danger";
    if (severity === "Medium") return "bg-warning text-dark";
    if (severity === "Low") return "bg-success";
    return "bg-primary";
}

async function logout() {
    await fetch(API_URL + "logout.php");
    localStorage.removeItem("user");
    localStorage.removeItem("admin");
    window.location.href = "index.html";
}
