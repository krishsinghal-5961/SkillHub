<?php
// db.php is assumed to be included in the same directory
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID
$user_id = $_SESSION['user']; // Replace this with the actual session user identifier

// Handle new recruitment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submitRecruitment') {
    $skillNeeded = $_POST['skillNeeded'];
    $description = $_POST['description'];
    $duration = (int)$_POST['duration'];
    $peopleRequired = (int)$_POST['peopleRequired'];

    // Insert new recruitment into the database
    $stmt = $conn->prepare("INSERT INTO recruitments (skill_needed, description, duration, people_required, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssii", $skillNeeded, $description, $duration, $peopleRequired);
    $stmt->execute();
    $stmt->close();

    // Respond with success
    echo json_encode(['status' => 'success']);
    exit();
}

// Handle application submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'applyForPosition') {
    $recruitment_id = (int)$_POST['recruitment_id'];

    // Check if the user has already applied
    $stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND recruitment_id = ?");
    $stmt->bind_param("ii", $user_id, $recruitment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already applied for this position.']);
        exit();
    }

    // Check if the application limit is not exceeded
    $stmt = $conn->prepare("SELECT applications, people_required FROM recruitments WHERE recruitment_id = ?");
    $stmt->bind_param("i", $recruitment_id);
    $stmt->execute();
    $stmt->bind_result($applications, $people_required);
    $stmt->fetch();
    $stmt->close();

    if ($applications < $people_required) {
        // Add the application and update recruitment
        $stmt = $conn->prepare("INSERT INTO applications (user_id, recruitment_id, applied_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $user_id, $recruitment_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE recruitments SET applications = applications + 1 WHERE recruitment_id = ?");
        $stmt->bind_param("i", $recruitment_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Application limit reached for this position.']);
    }
    exit();
}

// Fetch recruitments to display on the page
$recruitments = [];
$query = "SELECT * FROM recruitments WHERE applications < people_required AND TIMESTAMPDIFF(DAY, created_at, NOW()) <= duration";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $recruitments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Recruitment</title>
    <style>
        
        /* General Styles */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #000;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode {
    background-color: #1b1b1b; /* Updated dark background */
    color: #d1d1d1; /* Light text for readability */
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode header {
    background-color: #2b2b2b; /* Darker header background */
}

nav a {
    color: #000;
    text-decoration: none;
    margin-left: 25px;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav a:hover {
    color: #007bff;
}

.logo img{
    height: 50px;
}

body.dark-mode nav a {
    color: white; /* Soft blue links */
}

body.dark-mode nav a:hover {
    color: #80b3ff; /* Lighter blue on hover */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #4fc3f7; /* Theme blue */
    color: #1b1b1b; /* Dark text */
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dark-mode-toggle:hover {
    background-color: #009adf; /* Deeper blue */
}

/* Main Section */
main {
    width: 90%;
    max-width: 900px;
    margin: 30px auto;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode main {
    background-color: #252525; /* Darker background */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
}

h1 {
    text-align: center;
    color: #007bff;
    margin-bottom: 20px;
}

body.dark-mode h1 {
    color: #80b3ff; /* Light blue headings */
}

/* Form Container */
.form-container {
    max-width: 400px;
    margin: 0 auto 30px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode .form-container {
    background-color: #2f2f2f; /* Dark form background */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
}

.form-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

body.dark-mode .form-container label {
    color: #e0e0e0;
}

.form-container input,
.form-container textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

body.dark-mode .form-container input,
body.dark-mode .form-container textarea {
    background-color: #444; /* Darker input fields */
    color: #e0e0e0;
    border: 1px solid #555;
}

.form-container button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.form-container button:hover {
    background-color: #0056b3;
}

/* Recruitment List */
.recruitment-list {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

body.dark-mode .recruitment-list {
    background-color: #3a3a3a; /* Softer dark background */
}

.recruitment-item {
    padding: 15px;
    margin-bottom: 20px;
    background-color: #f0f0f0;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

body.dark-mode .recruitment-item {
    background-color: #4b4b4b; /* Dark recruitment items */
}

.recruitment-item h3 {
    color: #333;
}

body.dark-mode .recruitment-item h3 {
    color: #d1d1d1; /* Light text */
}

.recruitment-item p {
    color: #666;
}

body.dark-mode .recruitment-item p {
    color: #b0b0b0;
}

/* Buttons */
.btn {
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
}

.btn a {
    color: white;
    text-decoration: none;
}

.btn:hover {
    background-color: #0056b3;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #2b2b2b;
    color: white;
    margin-top: 40px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode footer {
    background-color: #222; /* Darker footer */
    color: #d1d1d1;
}


    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="dashboard.php">
            <img src="logo.png" alt="SkillHub Logo">
        </a>
    </div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
        <button id="darkModeToggle" class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>

<main>
    <h1>Recruitment Form</h1>
    <div class="form-container">
        <form id="recruitmentForm">
            <label for="skillNeeded">Skill Needed</label>
            <input type="text" id="skillNeeded" name="skillNeeded" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="duration">Duration (days)</label>
            <input type="number" id="duration" name="duration" required>

            <label for="peopleRequired">Number of People Required</label>
            <input type="number" id="peopleRequired" name="peopleRequired" required>

            <button type="button" onclick="submitRecruitment()">Submit</button>
        </form>
    </div>

    <h1>Recruitment Needs</h1>
    <div class="recruitment-list" id="recruitmentList">
        <?php foreach ($recruitments as $recruitment): ?>
            <div class="recruitment-item" data-id="<?= $recruitment['recruitment_id'] ?>">
                <h3><?= htmlspecialchars($recruitment['skill_needed']) ?></h3>
                <p><?= htmlspecialchars($recruitment['description']) ?></p>
                <p>Duration: <?= $recruitment['duration'] ?> days</p>
                <p>People Required: <?= $recruitment['people_required'] ?></p>
                <p>Applications: <?= $recruitment['applications'] ?></p>
                <button onclick="applyForPosition(<?= $recruitment['recruitment_id'] ?>)">Apply Now</button>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<footer>
<p>&copy; <?php echo date("Y"); ?> SkillHub</p>
</footer>

<script>
    function submitRecruitment() {
        const formData = new FormData(document.getElementById("recruitmentForm"));
        formData.append("action", "submitRecruitment");

        fetch("recruitment.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Recruitment form submitted!");
                location.reload();
            } else {
                alert("Failed to submit recruitment.");
            }
        });
    }

    function applyForPosition(recruitmentId) {
        const formData = new FormData();
        formData.append("action", "applyForPosition");
        formData.append("recruitment_id", recruitmentId);

        fetch("recruitment.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                location.reload();
            }
        });
    }

    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
        const mode = document.body.classList.contains("dark-mode") ? "enabled" : "disabled";
        localStorage.setItem("darkMode", mode);
        document.getElementById("darkModeToggle").textContent = mode === "enabled" ? "🌞 Light Mode" : "🌙 Dark Mode";
    }

    window.onload = function () {
        if (localStorage.getItem("darkMode") === "enabled") {
            document.body.classList.add("dark-mode");
            document.getElementById("darkModeToggle").textContent = "🌞 Light Mode";
        }
    };
</script>

</body>
</html>
