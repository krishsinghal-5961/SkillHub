<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "skillhub", "4306");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add skill to the database if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addSkill'])) {
    $skill = $_POST['skill'];
    $userId = $_SESSION['user'];

    $stmt = $conn->prepare("INSERT INTO SKILLS (skill_name, user_id) VALUES (?, ?)");
    $stmt->bind_param("si", $skill, $userId);
    $stmt->execute();
    $stmt->close();
}

// Remove skill from the database if requested
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeSkill'])) {
    $skillId = $_POST['skillId'];
    $stmt = $conn->prepare("DELETE FROM SKILLS WHERE skill_id = ?");
    $stmt->bind_param("i", $skillId);
    $stmt->execute();
    $stmt->close();
}

// Fetch skills from the database for the current user
$userId = $_SESSION['user'];
$skills = [];
$result = $conn->query("SELECT skill_id, skill_name FROM SKILLS WHERE user_id = $userId");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SkillHub - Skills Form</title>
    <style>
           
        /* General Styles */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: #1e1e1e;
    color: #dcdcdc;
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
    transition: background-color 0.3s ease;
}

body.dark-mode header {
    background-color: #2b2b2b;
}

nav a {
    color: #000;
    text-decoration: none;
    margin-left: 25px;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s ease;
}

body.dark-mode nav a {
    color: #dcdcdc;
}

nav a:hover {
    color: #80b3ff;
}

/* Main Content */
main {
    padding: 20px;
    background-color: #ffffff;
    max-width: 900px;
    margin: 30px auto;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    transition: background-color 0.3s ease;
}

body.dark-mode main {
    background-color: #2d2d2d;
}

h1 {
    text-align: center;
    color: #007bff;
    margin-bottom: 30px;
}

body.dark-mode h1 {
    color: #80b3ff;
}

.form-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

body.dark-mode .form-container {
    background-color: #333333;
    border-color: #444;
}

.form-container label {
    display: block;
    margin-bottom: 5px;
}

body.dark-mode .form-container label {
    color: #dcdcdc;
}

.form-container input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.logo-img{
    height:50px;
}
body.dark-mode .form-container input {
    background-color: #444444;
    color: #dcdcdc;
    border-color: #666;
}

button {
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

body.dark-mode button {
    background-color: #0056b3;
}

button:hover {
    background-color: #0056b3;
}

/* Subheadings */
h2 {
    color: #007bff;
    text-align: center;
    margin-top: 40px;
}

body.dark-mode h2 {
    color: #80b3ff;
}

/* List Items */
ul {
    list-style-type: none;
    padding: 0;
    text-align: center;
}

ul li {
    padding: 10px;
    background-color: #f0f0f0;
    margin: 10px auto;
    display: flex;
    justify-content: space-between;
    max-width: 300px;
    border-radius: 5px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

body.dark-mode ul li {
    background-color: #3a3a3a;
    color: #dcdcdc;
}

/* Remove Button */
.remove-btn {
    background-color: #ff4c4c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 5px 10px;
}

.remove-btn:hover {
    background-color: #d43f3f;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333333;
    color: white;
    margin-top: 40px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode footer {
    background-color: #222222;
    color: #dcdcdc;
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease;
}

.dark-mode-toggle:hover {
    background-color: #0056b3;
}


    </style>
</head>

<body>
    <header>
        <div class="logo">
            <a href="dashboard.php">
                <img src="logo.png" alt="SkillHub Logo" class="logo-img">
            </a>
        </div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        </nav>
    </header>

    <main>
        <h1>Skills Form</h1>
        <div class="form-container">
            <form id="skillsForm" method="POST" action="">
                <label for="skill">Enter Your Skill</label>
                <input type="text" id="skill" name="skill" placeholder="Enter your skill" required>
                <button type="submit" name="addSkill">Submit</button>
            </form>
        </div>

        <h2>Current Skills</h2>
        <ul id="currentSkills">
            <?php foreach ($skills as $skill): ?>
                <li>
                    <?= htmlspecialchars($skill['skill_name']) ?>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="skillId" value="<?= $skill['skill_id'] ?>">
                        <button type="submit" name="removeSkill" class="remove-btn">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
            <?php if (empty($skills)): ?>
                <li>No skills added yet.</li>
            <?php endif; ?>
        </ul>
    </main>
    
    <footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub</p>
    </footer>

    <script>
      function addSkill(event) {
            event.preventDefault();
            const skillInput = document.getElementById('skill');
            const skill = skillInput.value;
            const li = document.createElement('li');
            li.innerHTML = `${skill} <button class="remove-btn" onclick="removeSkill(this)">Remove</button>`;
            document.getElementById('currentSkills').appendChild(li);
            skillInput.value = '';
        }

        function removeSkill(button) {
            const li = button.parentElement;
            li.remove();
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const darkModeToggle = document.querySelector(".dark-mode-toggle");

            if (document.body.classList.contains('dark-mode')) {
                darkModeToggle.textContent = "🌞 Light Mode";
                localStorage.setItem('darkMode', 'enabled');
            } else {
                darkModeToggle.textContent = "🌙 Dark Mode";
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Check for saved user preference on page load
        window.onload = function () {
            const darkModePreference = localStorage.getItem('darkMode');
            const darkModeToggle = document.querySelector(".dark-mode-toggle");

            if (darkModePreference === 'enabled') {
                document.body.classList.add('dark-mode');
                darkModeToggle.textContent = "🌞 Light Mode";
            } else {
                darkModeToggle.textContent = "🌙 Dark Mode";
            }
        };


    </script>
</body>
</html>
