<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user'] ?? null;
if (!$user_id) {
    die("User not logged in");
}

// Fetch the user's connections
$sql = "SELECT c.connection_user_id AS connection_id, u.full_name AS connection_name
        FROM connections c
        JOIN users u ON c.connection_user_id = u.user_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Messages</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: #121212;
            color: #f1f1f1;
        }

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background-color 0.3s, color 0.3s;
        }

        nav a {
            color: #000;
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s;
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
            transition: background-color 0.3s, color 0.3s;
        }
            .logo-img{
                height:50px;
            }
        /* Main Content */
        main {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        /* Contacts List */
        .contacts-list {
            width: 30%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
        }

        .contacts-list h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .contacts-list ul {
            list-style: none;
            padding: 0;
        }

        .contacts-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .contacts-list li button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
            transition: background-color 0.3s, color 0.3s;
        }

 /* Dark Mode Styles */
body.dark-mode {
    background-color: #1e1e1e; /* Darker background */
    color: #f5f5f5; /* Light gray text */
}

/* Header */
body.dark-mode header {
    background-color: #2a2a2a; /* Dark gray for the header */
}

/* Navigation Links */
body.dark-mode nav a {
    color: #f5f5f5; /* Light text for navigation links */
}

body.dark-mode nav a:hover {
    color: #90caf9; /* Light blue for hover effect */
}

/* Dark Mode Toggle Button */
body.dark-mode .dark-mode-toggle {
    background-color: #90caf9; /* Light blue for toggle button */
    color: #2c2c2c; /* Dark background for text on button */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #60a4e2; /* Slightly darker blue on hover */
}

/* Contacts List */
body.dark-mode .contacts-list {
    background-color: #444; /* Slightly lighter dark for contacts list */
    color: #f5f5f5; /* Light text for readability */
}

/* Footer */
body.dark-mode footer {
    background-color: #3a3a3a; /* Dark gray for footer */
    color: #f5f5f5; /* Light text in the footer */
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
        <div class="contacts-list">
            <h2>Contacts</h2>
            <ul>
                <?php foreach ($contacts as $contact): ?>
                    <li>
                        <span><?php echo htmlspecialchars($contact['connection_name']); ?></span>
                        <a href="chat.php?connection_id=<?php echo $contact['connection_id']; ?>&name=<?php echo urlencode($contact['connection_name']); ?>">
                            <button>Chat</button>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>

    <footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub</p>
    </footer>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const darkModeEnabled = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', darkModeEnabled ? 'enabled' : 'disabled');
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
