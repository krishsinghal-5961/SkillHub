<?php
// Include the database connection file
session_start();
include 'db.php'; // Ensures your database connection file is loaded

// Redirect if not logged in
if (isset($_SESSION['user'])) {
    
} else {
    echo "No user session set.";
    header("Location: login.php");
    exit;
}

// Initialize message for form submission
$enrollmentMessage = "";

// Fetch courses from the database for the dropdown
$courseOptions = "";
$courseQuery = "SELECT course_id, title FROM courses";
$courseResult = $conn->query($courseQuery);

if ($courseResult->num_rows > 0) {
    while ($row = $courseResult->fetch_assoc()) {
        $courseOptions .= "<option value='{$row['course_id']}'>{$row['title']}</option>";
    }
} else {
    $courseOptions = "<option value=''>No courses available</option>";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $enrollmentId = trim($_POST['enrollmentId']);
    $selectedCourse = trim($_POST['selectedCourse']);
    $userId = $_SESSION['user']; // Placeholder for user ID (e.g., from a session variable)

    // Validate Enrollment ID format and course selection
    if (!preg_match('/^\d{8}$/', $enrollmentId)) {
        $enrollmentMessage = "Please enter a valid 8-digit Enrollment ID.";
    } elseif (empty($selectedCourse)) {
        $enrollmentMessage = "Please select a course.";
    } else {
        // Check for duplicate enrollment ID
        $checkQuery = "SELECT * FROM enrollments WHERE enrollment_id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $enrollmentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $enrollmentMessage = "This enrollment ID is already in use.";
        } else {
            // Insert enrollment data into the database
            $progressPercentage = 0; // Start progress at 0%
            $enrolledAt = date('Y-m-d H:i:s');

            $insertQuery = "INSERT INTO enrollments (enrollment_id, user_id, course_id, progress_percentage, enrolled_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iiids", $enrollmentId, $userId, $selectedCourse, $progressPercentage, $enrolledAt);

            if ($stmt->execute()) {
                $enrollmentMessage = "You have successfully enrolled in the course!";
            } else {
                $enrollmentMessage = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SkillHub - Enrollment</title>
    <style>
               /* General Styling */
               body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F0F2F5;
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

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        main {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #333;
            color: white;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode Theme */
/* Dark Mode Theme */
body.dark-mode {
    background-color: #121212; /* Dark background for a sleek look */
    color: #e0e0e0; /* Soft light text for readability */
}

/* Header Styles */
body.dark-mode header {
    background-color: #1f1f1f; /* Dark gray header for depth */
}

/* Navigation Styles */
body.dark-mode nav a {
    color: #e0e0e0; /* Light gray link color */
}

body.dark-mode nav a:hover {
    color: #90caf9; /* Light blue on hover for visual feedback */
}

/* Toggle Button Styles */
body.dark-mode .dark-mode-toggle {
    background-color: #90caf9; /* Toggle button in light blue */
    color: #121212; /* Dark text color for contrast */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #60a4e2; /* Slightly darker blue on hover */
}

/* Main Content Styles */
body.dark-mode main {
    background-color: #2a2a2a; /* Dark gray for main content */
}

/* Form Container Styles */
body.dark-mode .form-container {
    background-color: #2c2c2c; /* Matching dark background */
    color: #e0e0e0; /* Light text in forms */
}

body.dark-mode .form-container input,
body.dark-mode .form-container select,
body.dark-mode .form-container textarea {
    background-color: #3a3a3a; /* Darker inputs for better contrast */
    color: #f0f0f0; /* Light text in inputs */
    border: 1px solid #444; /* Subtle border for inputs */
}

body.dark-mode .form-container button {
    background-color: #90caf9; /* Light blue buttons for prominence */
    color: #121212; /* Dark text for button labels */
}

/* Footer Styles */
body.dark-mode footer {
    background-color: #1f1f1f; /* Dark gray footer */
    color: #e0e0e0; /* Light text in footer */
}

</style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="dashboard.php"><img src="logo.png" alt="SkillHub Logo"></a>
        </div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        </nav>
    </header>

    <main>
        <h1>Enroll in Course</h1>
        <div class="form-container">
            <!-- Display enrollment message -->
            <?php if ($enrollmentMessage): ?>
                <p style="color: <?= strpos($enrollmentMessage, 'successfully') !== false ? 'green' : 'red' ?>;">
                    <?= htmlspecialchars($enrollmentMessage) ?>
                </p>
            <?php endif; ?>

            <!-- Enrollment Form -->
            <form id="enrollmentForm" method="post" action="enrollment.php" onsubmit="return validateForm()">
                <label for="enrollmentId">Enrollment ID</label>
                <input type="text" id="enrollmentId" name="enrollmentId" placeholder="Enter your 8-digit Enrollment ID" required pattern="\d{8}">

                <label for="selectedCourse">Select Course</label>
                <select id="selectedCourse" name="selectedCourse" required>
                    <option value="">Select a course</option>
                    <?= $courseOptions ?>
                </select>

                <button type="submit">Enroll</button>
            </form>
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

        function validateForm() {
            const enrollmentId = document.getElementById('enrollmentId').value;
            const selectedCourse = document.getElementById('selectedCourse').value;
            if (!/^\d{8}$/.test(enrollmentId)) {
                alert("Please enter a valid 8-digit Enrollment ID.");
                return false;
            }
            if (selectedCourse === "") {
                alert("Please select a course.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
