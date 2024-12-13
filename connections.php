<?php
// Start session and include database connection
session_start();
require_once 'db.php';

// Assuming the logged-in user's ID is stored in the session
$user_id = $_SESSION['user'] ?? null;

// Redirect if no user is logged in
if (!$user_id) {
    header("Location: login.html");
    exit;
}

// Function to retrieve all users except the logged-in user
function getAllUsersExcept($user_id) {
    global $conn;
    // Modify the query to order by full_name alphabetically
    $sql = "SELECT user_id, full_name, profile_image FROM users WHERE user_id != ? ORDER BY full_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
    return $users;
}

// Function to add a connection if it doesn't already exist
function addConnection($user_id, $connection_user_id) {
    global $conn;

    // Check if the connection already exists
    $sql = "SELECT * FROM connections WHERE user_id = ? AND connection_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $connection_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Connection already exists.";
    } else {
        // Add the new connection
        $sql = "INSERT INTO connections (user_id, connection_user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $connection_user_id);

        if ($stmt->execute()) {
            // Fetch the full name of the user who is making the connection
            $sql = "SELECT full_name FROM users WHERE user_id = ?";
            $name_stmt = $conn->prepare($sql);
            $name_stmt->bind_param("i", $user_id);
            $name_stmt->execute();
            $name_result = $name_stmt->get_result();
            $user_full_name = '';

            if ($name_result->num_rows > 0) {
                $row = $name_result->fetch_assoc();
                $user_full_name = $row['full_name'];
            }

            // Insert a notification for the connected user
            $message = "$user_full_name added you as a connection.";
            $type = "connection_request";
            $read_status = 0; // 0 means unread

            $notification_sql = "INSERT INTO notifications (user_id, message, type, read_status, created_at) VALUES (?, ?, ?, ?, NOW())";
            $notification_stmt = $conn->prepare($notification_sql);
            $notification_stmt->bind_param("issi", $connection_user_id, $message, $type, $read_status);

            // Execute the notification insertion and handle any errors
            if ($notification_stmt->execute()) {
                return "Connection added successfully and notification sent!";
            } else {
                print("Error inserting notification: " . $notification_stmt->error);
                return "Connection added, but failed to send notification.";
            }
        } else {
            print("Error adding connection: " . $stmt->error);
            return "Error adding connection: " . $stmt->error;
        }
    }
}


// Handle connection requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connection_user_id'])) {
    $connection_user_id = intval($_POST['connection_user_id']);
    if ($connection_user_id != $user_id) { // Prevent self-connection
        $message = addConnection($user_id, $connection_user_id);
    } else {
        $message = "You cannot connect with yourself.";
    }
}

// Fetch all users except the logged-in user to display as potential connections
$all_users = getAllUsersExcept($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Connections</title>
    <style>
       /* General Styles */
 /* General Styling */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F9F9F9;
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

main {
    width: 90%;
    max-width: 1200px;
    margin: 30px auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.connections-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.connection-card {
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 10px;
    text-align: center;
    width: 200px;
    transition: transform 0.3s ease-in-out;
}

.connection-card:hover {
    transform: translateY(-5px);
}

.connection-card img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    margin-top: 10px;
}

.btn:hover {
    background-color: #0056b3;
}

footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: white;
    margin-top: 40px;
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: #1b1b1b;
    color: #e0e0e0;
}

/* Dark Mode Header */
body.dark-mode header {
    background-color: #2a2a2a;
    color: #f0f0f0;
}

/* Dark Mode Navigation Links */
body.dark-mode nav a {
    color: #e0e0e0;
}

body.dark-mode nav a:hover {
    color: #4db8ff;
}

/* Dark Mode Toggle Button */
body.dark-mode .dark-mode-toggle {
    background-color: #4db8ff;
    color: #1b1b1b;
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #007bff;
    color: #ffffff;
}

/* Dark Mode Connection Card */
body.dark-mode .connection-card {
    background-color: #2b2b2b;
    color: #e0e0e0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Dark Mode Buttons */
body.dark-mode .btn {
    background-color: #4db8ff;
    color: #1b1b1b;
}

body.dark-mode .btn:hover {
    background-color: #007bff;
    color: #ffffff;
}

/* Dark Mode Footer */
body.dark-mode footer {
    background-color: #2a2a2a;
    color: #f0f0f0;
}


        /* Transition for smooth dark mode */
        * {
            transition: background-color 0.3s ease, color 0.3s ease;
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
        <h1>Connect with Other Users</h1>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <div class="connections-list">
    <?php foreach ($all_users as $user): ?>
        <div class="connection-card">
            <!-- Make the image clickable and redirect to user_profile.php with the user's ID -->
            <a href="user_profile.php?user_id=<?php echo $user['user_id']; ?>">
                <img src="<?php echo htmlspecialchars($user['profile_image'] ?: 'default.jpg'); ?>"
                     alt="<?php echo htmlspecialchars($user['full_name']); ?>"
                     style="width:100px; height:100px; border-radius:50%; margin-bottom:10px;">
            </a>
            <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
            <!-- Connect button remains here -->
            <form method="POST" action="connections.php">
                <input type="hidden" name="connection_user_id" value="<?php echo $user['user_id']; ?>">
                <button class="btn" type="submit">Connect</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

    </main>

    <footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub</p>
    </footer>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        window.onload = function () {
            const darkModePreference = localStorage.getItem('darkMode');
            if (darkModePreference === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        };
    </script>
</body>
</html>
