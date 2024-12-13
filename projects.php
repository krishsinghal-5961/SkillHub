<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    die("You must be logged in to view this page.");
}

$user_id = $_SESSION['user'];

// Handle form submission
$submissionMessage = ""; // Initialize message variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_title = $conn->real_escape_string($_POST['project_title']);
    $project_description = $conn->real_escape_string($_POST['project_description']);

    // Insert project into the database
    $sql = "INSERT INTO projects (user_id, project_title, project_description) VALUES ('$user_id', '$project_title', '$project_description')";

    if ($conn->query($sql) === TRUE) {
        $submissionMessage = "Project submitted successfully!";
    } else {
        $submissionMessage = "Error: " . $conn->error;
    }
}

// Retrieve all projects by the logged-in user
$sql = "SELECT project_title, project_description, created_at FROM projects WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Projects</title>
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
    background-color: #1e1e1e; /* Theme's dark background */
    color: #e0e0e0; /* Softer white for readability */
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode header {
    background-color: #2a2a2a; /* Dark header */
}

.logo img {
    height: 50px;
}

nav {
    display: flex;
    align-items: center;
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

body.dark-mode nav a {
    color: #e0e0e0;
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #81d4fa; /* Theme blue */
    color: #1e1e1e; /* Dark text */
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dark-mode-toggle:hover {
    background-color: #4fc3f7; /* Slightly deeper blue */
}

/* Main Section */
main {
    width: 90%;
    max-width: 900px;
    margin: 30px auto;
    padding: 20px;
}

h1, h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 20px;
}

body.dark-mode h1, body.dark-mode h2 {
    color: #81d4fa; /* Theme blue for headings */
}

/* Form Container */
.form-container {
    max-width: 500px;
    margin: 0 auto 30px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode .form-container {
    background-color: #2a2a2a; /* Dark background */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
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
    background-color: #333333; /* Darker input background */
    color: #e0e0e0;
    border: 1px solid #555;
}

.form-container button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.form-container button:hover {
    background-color: #0056b3;
}

/* Project List */
.project-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 800px;
    margin: 20px auto;
}

.project-item {
    padding: 15px;
    background-color: #f0f8ff;
    border-left: 5px solid #007bff;
    border-radius: 10px;
    transition: background-color 0.3s;
}

body.dark-mode .project-item {
    background-color: #333333; /* Darker project item */
}

.project-item h3 {
    color: #007bff;
    font-size: 18px;
    margin-bottom: 8px;
}

body.dark-mode .project-item h3 {
    color: #81d4fa; /* Theme blue */
}

.project-item p {
    color: #444;
    font-size: 15px;
    margin-bottom: 5px;
}

body.dark-mode .project-item p {
    color: #b0bec5; /* Softer gray text */
}

.project-item small {
    color: #777;
    font-size: 13px;
}

body.dark-mode .project-item small {
    color: #bbb; /* Light gray */
}

/* Alert Styles */
.alert {
    max-width: 500px;
    margin: 10px auto;
    padding: 10px;
    border-radius: 5px;
    background-color: #d4edda;
    color: #155724;
    text-align: center;
    font-weight: bold;
}

body.dark-mode .alert {
    background-color: #2a7d2f; /* Darker green for alert */
    color: #e0f3e1; /* Soft green for text */
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    padding: 20px;
    text-align: center;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode footer {
    background-color: #2a2a2a;
    color: #e0e0e0;
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



<h1>Submit a New Project</h1>
<form action="projects.php" method="POST" class="form-container">
    <label for="project_title">Project Title:</label>
    <input type="text" name="project_title" id="project_title" required>
    
    <label for="project_description">Project Description:</label>
    <textarea name="project_description" id="project_description" required></textarea>
    
    <button type="submit">Submit Project</button>
</form>

<h2>Your Projects</h2>
<?php if ($result->num_rows > 0): ?>
    <div class="project-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="project-item">
                <h3><?php echo htmlspecialchars($row['project_title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['project_description'])); ?></p>
                <small>Submitted on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></small>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No projects found. Submit your first project above!</p>
<?php endif; ?>
<footer>
<p>&copy; <?php echo date("Y"); ?> SkillHub</p>
    </footer>
<script>
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
