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
    <title>SkillHub - Courses</title>

    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f5;
            color: #333;
            transition: background-color 0.3s ease, color 0.3s ease;
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
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark-mode-toggle:hover {
            background-color: #0056b3;
        }

        /* Main Layout */
        main {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 2rem;
            color: #333;
            text-align: center;
        }

        /* Request to Add Course Button */
        .button-container {
    display: flex;
    justify-content: center; /* Centers the button horizontally */
    width: 100%; /* Makes sure the container spans the full width */
    margin-top: 20px; /* Adds space above the button */
}

.request-button {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: bold;
    margin-bottom: 20px;
    transition: background-color 0.3s ease, color 0.3s ease;
}


        .request-button:hover {
            background-color: #0056b3;
        }

        /* Course List */
        .courses-list {
            display: center;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            width: 100%;
        }

        .course {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .course h3 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .course p {
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
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
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode Styles */
        body.dark-mode {
    background-color: #1e1e1e; /* Darker background for a smoother look */
    color: #e0e0e0; /* Softer white for less strain on the eyes */
}

body.dark-mode header {
    background-color: #2a2a2a; /* Slightly darker header */
}

body.dark-mode nav a {
    color: #e0e0e0; /* Keeping links the same color for consistency */
}

body.dark-mode nav a:hover {
    color: #81d4fa; /* A lighter blue for hover effect */
}

body.dark-mode .dark-mode-toggle {
    background-color: #81d4fa; /* A fresh, lighter blue */
    color: #1e1e1e; /* Darker background for button */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #4fc3f7; /* A deeper blue on hover */
    color: white; /* Keep text white for visibility */
}

body.dark-mode h1 {
    color: #e0e0e0; /* Consistent heading color */
}

body.dark-mode .course {
    background-color: #333333; /* A moderate shade for course cards */
    color: #e0e0e0; /* Consistent text color */
}

body.dark-mode .course h3 {
    color: #81d4fa; /* Lighter blue for headings in course sections */
}

body.dark-mode .course p {
    color: #b0bec5; /* Softer gray for paragraph text */
}

body.dark-mode footer {
    background-color: #2a2a2a; /* Consistent with header */
    color: #e0e0e0; /* Keep footer text consistent */
}

        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease;
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
        <!-- Request to Add Course Button -->
        
        <h1>Available Courses</h1>
        <div class="courses-list">
            <div class="course">
                <h3>Advanced JavaScript</h3>
                <p>This course covers advanced concepts in JavaScript, including ES6 features, asynchronous programming, and modern frameworks.</p>
                <button class="btn"><a href="course.php">View Details</a></button>
            </div>

            <div class="button-container">
        <a href="https://forms.gle/RtekussV5QaDLhVt5" class="request-button" target="_blank">Request to Add Course</a>
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
