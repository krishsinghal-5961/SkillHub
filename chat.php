<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user'] ?? null;
if (!$user_id) {
    die("User not logged in");
}

$connection_id = $_GET['connection_id'] ?? null;
$connection_name = $_GET['name'] ?? 'Contact';

if (!$connection_id) {
    die("Connection ID not provided.");
}

// Fetch previous messages
$messages = [];
$sql = "SELECT sender_id, content, sent_at 
        FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $connection_id, $connection_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();

// Handle new message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';

    if ($content) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $connection_id, $content);
        $stmt->execute();
        $stmt->close();

        header("Location: chat.php?connection_id=$connection_id&name=" . urlencode($connection_name));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($connection_name); ?></title>
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F9F9F9;
    color: #333;
    transition: background-color 0.3s, color 0.3s;
}

/* Dark Mode */
body.dark-mode {
    background-color: #121212; /* Dark background */
    color: #e0e0e0; /* Softer light text for less strain */
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: background-color 0.3s, color 0.3s;
}

body.dark-mode header {
    background-color: #2a2a2a; /* Darker gray header */
    color: #e0e0e0;
}

/* Navbar Links */
nav a {
    color: #000;
    text-decoration: none;
    margin-left: 25px;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s;
}

.logo img {
    height: 50px; /* Smaller logo size */
}

body.dark-mode nav a {
    color: #e0e0e0; /* Light text for dark mode */
}

body.dark-mode nav a:hover {
    color: #81d4fa; /* Light blue hover effect */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    cursor: pointer;
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 25px;
    transition: background-color 0.3s;
}

body.dark-mode .dark-mode-toggle {
    background-color: #81d4fa;
    color: #1e1e1e;
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #4fc3f7;
    color: #fff;
}

/* Chat Box */
.chat-box {
    width: 60%;
    max-width: 800px;
    margin: 40px auto;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    display: flex;
    flex-direction: column;
    transition: background-color 0.3s;
}

body.dark-mode .chat-box {
    background-color: #1e1e1e; /* Darker background for chat box */
}

/* Messages */
.messages {
    flex-grow: 1;
    overflow-y: auto;
    padding: 15px;
    border-radius: 10px;
    background-color: #f7f7f7;
    margin-bottom: 20px;
    max-height: 400px;
}

body.dark-mode .messages {
    background-color: #2c2c2c; /* Darker background for messages */
}

.message {
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 10px;
    max-width: 70%;
    word-wrap: break-word;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.message.sent {
    align-self: flex-end;
    background-color: #007bff;
    color: #fff;
    border-bottom-right-radius: 0;
}

.message.received {
    align-self: flex-start;
    background-color: #444; /* Darker shade for received messages */
    color: #f1f1f1;
    border-bottom-left-radius: 0;
}

.message .timestamp {
    font-size: 12px;
    opacity: 0.7;
    margin-top: 5px;
}

/* Input Form */
.send-message {
    display: flex;
    align-items: center;
    gap: 10px;
}

.send-message textarea {
    flex: 1;
    resize: none;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
    min-height: 50px;
}

body.dark-mode .send-message textarea {
    background-color: #333; /* Dark background for input area */
    color: #e0e0e0;
    border-color: #444;
}

/* Send Button */
.send-message button {
    background-color: #ff4c4c; /* Red color */
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    margin-left: 10px;
    cursor: pointer;
    font-size: 1.2rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
}

body.dark-mode .send-message button {
    background-color: #e04343; /* Slightly darker red in dark mode */
}

.send-message button:hover {
    background-color: #d03939; /* Darker red on hover in dark mode */
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: white;
    margin-top: 40px;
}

body.dark-mode footer {
    background-color: #2a2a2a; /* Consistent dark footer */
    color: #e0e0e0;
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
        <a href="messages.php">Messages</a>
        <a href="logout.php">Logout</a>
        <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>
<main>
    <div class="chat-box">
        <h2>Chat with <?php echo htmlspecialchars($connection_name); ?></h2>

        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo $message['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                    <?php echo htmlspecialchars($message['content']); ?>
                    <div class="timestamp"><?php echo $message['sent_at']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form class="send-message" method="POST">
            <textarea name="content" placeholder="Type your message here..." required></textarea>
            <button type="submit">&#10148;</button>
        </form>
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

