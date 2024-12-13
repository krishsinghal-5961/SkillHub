<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo "You must be logged in to create a post.";
    exit;
}

require_once 'db.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_title = isset($_POST['post_title']) ? trim($_POST['post_title']) : '';
    $post_content = isset($_POST['post_content']) ? trim($_POST['post_content']) : '';
    $user_id = $_SESSION['user'];

    if (empty($post_content)) {
        $error_message = "Post content cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (post_title, post_content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $post_title, $post_content, $user_id);

        if ($stmt->execute()) {
            $success_message = "New post created successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
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

/* Main Content */
main {
    width: 90%;
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Form Container */
.form-container {
    max-width: 500px;
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
.form-container textarea {
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

/* Footer */
footer {
    background-color: #333;
    color: white;
    padding: 20px;
    text-align: center;
    transition: background-color 0.3s ease, color 0.3s ease;
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

/* Dark Mode Main Content */
body.dark-mode main {
    background-color: #252525;
    color: #f0f0f0;
}

/* Dark Mode Form Container */
body.dark-mode .form-container {
    background-color: #2b2b2b;
    border-color: #4db8ff;
    color: #e0e0e0;
}

/* Dark Mode Input Fields and Textareas */
body.dark-mode .form-container input,
body.dark-mode .form-container textarea {
    background-color: #3a3a3a;
    color: #f0f0f0;
    border-color: #555;
}

/* Dark Mode Form Buttons */
body.dark-mode .form-container button {
    background-color: #4db8ff;
    color: #1b1b1b;
}

body.dark-mode .form-container button:hover {
    background-color: #007bff;
    color: #ffffff;
}

/* Dark Mode Footer */
body.dark-mode footer {
    background-color: #2a2a2a;
    color: #f0f0f0;
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
        <h1>Create a New Post</h1>
        <div class="form-container">
            <form action="create_post.php" method="POST">
                <label for="post_title">Post Title</label>
                <input type="text" id="post_title" name="post_title" maxlength="255" placeholder="Enter post title">

                <label for="post_content">Post Content</label>
                <textarea id="post_content" name="post_content" rows="8" placeholder="Write your post content here..." required></textarea>

                <button type="submit">Submit Post</button>
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
    </script>
</body>
</html>

