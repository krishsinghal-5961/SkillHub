<?php
// Start session and include database connection
session_start();
require_once 'db.php';

// Check if the user is logged in
$user_id = $_SESSION['user'] ?? null;
if (!$user_id) {
    header("Location: login.html");
    exit;
}

// Function to retrieve notifications for the logged-in user
function getNotifications($user_id) {
    global $conn;
    $sql = "SELECT message, type, created_at FROM notifications WHERE user_id = ? AND read_status = 0 ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    $stmt->close();
    return $notifications;
}

// Fetch notifications for the logged-in user
$notifications = getNotifications($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Notifications</title>
    <style>
          body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F9F9F9;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

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
        }

        nav a {
            color: #000;
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .logo img {
            height: 50px;
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
        }

        .dark-mode-toggle:hover {
            background-color: #0056b3;
        }

        main {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            padding: 20px;
        }

        .notifications-container {
            width: 60%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .notifications-container h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #007bff;
        }

        .notification {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification:last-child {
            border-bottom: none;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
        }

        /* Dark Mode */
body.dark-mode {
    background-color: #1e1e1e; /* Darker background for a smoother look */
    color: #e0e0e0; /* Softer white for less strain on the eyes */
}

body.dark-mode header,
body.dark-mode footer {
    background-color: #2a2a2a; /* Slightly darker for header and footer */
}

body.dark-mode nav a {
    color: #e0e0e0; /* Consistent color for links */
}

body.dark-mode nav a:hover {
    color: #81d4fa; /* Lighter blue for hover effect */
}

body.dark-mode .dark-mode-toggle {
    background-color: #81d4fa; /* Fresh, lighter blue */
    color: #1e1e1e; /* Dark background for button */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #4fc3f7; /* Deep blue on hover */
    color: white; /* White text for visibility */
}

body.dark-mode .notifications-container {
    background-color: #333333; /* Moderate shade for notifications */
    color: #e0e0e0; /* Consistent text color */
}

body.dark-mode h1, 
body.dark-mode h2, 
body.dark-mode h3, 
body.dark-mode h4, 
body.dark-mode h5, 
body.dark-mode h6 {
    color: #e0e0e0; /* Uniform heading color */
}

body.dark-mode .course {
    background-color: #333333; /* Moderate shade for course cards */
    color: #e0e0e0; /* Consistent text color */
}

body.dark-mode .course h3 {
    color: #81d4fa; /* Light blue for course section headings */
}

body.dark-mode .course p {
    color: #b0bec5; /* Softer gray for paragraph text */
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
        <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>

<main>
    <div class="notifications-container">
        <h2>Notifications</h2>
        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification">
                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                    <small><?php echo htmlspecialchars($notification['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>
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

    document.addEventListener('DOMContentLoaded', function () {
        const darkMode = localStorage.getItem('darkMode');
        if (darkMode === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    });
</script>

</body>
</html>
