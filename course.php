<?php
session_start();
include 'db.php'; // Ensures your database connection file is loaded

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo "No user session set.";
    header("Location: login.php");
    exit;
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Course Info</title>
    <style>
/* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #eef2f5;
    transition: background-color 0.3s, color 0.3s;
}

body.dark-mode {
    background-color: #2a2a2a; /* Dark background */
    color: #e0e0e0; /* Light text for readability */
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
    transition: background-color 0.3s, color 0.3s;
}

body.dark-mode header {
    background-color: #333; /* Dark header */
    color: #f1f1f1;
}

/* Logo */
.logo img {
    height: 50px;
}

/* Navbar */
nav {
    display: flex;
    align-items: center;
}

nav a {
    color: #000; /* Light mode text color */
    text-decoration: none;
    margin-left: 25px;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s ease;
}

body.dark-mode nav a {
    color: #e0e0e0; /* Light text in dark mode */
}

nav a:hover {
    color: #007bff; /* Hover effect */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    cursor: pointer;
    padding: 10px;
    background-color: #007bff;
    border: none;
    color: #fff;
    border-radius: 25px;
    transition: background-color 0.3s ease;
    margin-left: 20px;
}


/* Main Layout */
main {
    width: 90%;
    max-width: 800px;
    margin: 40px auto;
    display: flex;
    flex-direction: column;
}

/* Heading */
h1 {
    font-size: 2rem;
    color: #333; /* Darker text in light mode */
    text-align: center;
    margin-bottom: 20px;
}

body.dark-mode h1 {
    color: #e0e0e0; /* Light text in dark mode */
}

/* Course Details */
.course-details {
    background-color: #fff; /* Light mode background */
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    transition: background-color 0.3s, color 0.3s;
}

body.dark-mode .course-details {
    background-color: #2c2c2c; /* Dark background */
    color: #e0e0e0;
}

.course-details h2 {
    font-size: 1.5rem;
    color: #007bff; /* Primary color */
    margin-bottom: 10px;
}

body.dark-mode .course-details h2 {
    color: #66b2ff; /* Softer blue for dark mode */
}

.course-details p {
    color: #555; /* Standard text color */
    margin-bottom: 10px;
}

body.dark-mode .course-details p {
    color: #ccc; /* Light text for readability in dark mode */
}

/* Button */
.btn {
    background-color: #007bff; /* Primary color */
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3; /* Darker shade on hover */
}



.btn a {
    color: white;
    text-decoration: none;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: white;
    margin-top: 40px;
    transition: background-color 0.3s;
}

body.dark-mode footer {
    background-color: #1f1f1f; /* Consistent footer with dark mode */
    color: #e0e0e0;
}

    </style>
</head>

<body>
    <header id="header">
        <div class="logo">
            <a href="dashboard.php">
                <img src="logo.png" alt="SkillHub Logo">
            </a>
        </div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        </nav>
    </header>

    <main>
        <h1>Course Information</h1>
        <div class="course-details">
            <h2>Course Title: Advanced JavaScript</h2>
            <p><strong>Description:</strong> This course covers advanced concepts in JavaScript, including ES6 features, asynchronous programming, and modern frameworks.</p>
            <p><strong>Instructor:</strong> Krish Singhal</p>
            <p><strong>Duration:</strong> 12 Weeks</p>
            
            <button class="btn"><a href="enrollment.php">Enroll in this Course</a></button>
        </div>
    </main>

    <footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub</p>
    </footer>

    <script>
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
