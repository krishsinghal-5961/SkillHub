<?php
// Start session and include the database connection
session_start();
include 'db.php';  // Ensure db.php contains the mysqli connection code

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');  // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['user'];

// Handle like button click
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_post_id'])) {
    $post_id = $_POST['like_post_id'];

    // Check if the user has already liked this post
    $checkLike = $conn->prepare("SELECT * FROM Likes WHERE user_id = ? AND post_id = ?");
    $checkLike->bind_param("ii", $user_id, $post_id);
    $checkLike->execute();
    $result = $checkLike->get_result();

    if ($result->num_rows === 0) {
        // Insert the like if not already liked
        $likeQuery = $conn->prepare("INSERT INTO Likes (post_id, user_id) VALUES (?, ?)");
        $likeQuery->bind_param("ii", $post_id, $user_id);
        $likeQuery->execute();
    }
    $checkLike->close();
}

// Fetch posts with the owner's name and like count
$query = "
    SELECT p.post_id, p.post_content, p.created_at, p.post_title, u.full_name,
           (SELECT COUNT(*) FROM Likes WHERE post_id = p.post_id) AS like_count,
           (SELECT COUNT(*) FROM Likes WHERE post_id = p.post_id AND user_id = ?) AS user_liked
    FROM Posts p
    JOIN Users u ON p.user_id = u.user_id
    ORDER BY p.created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts - SkillHub</title>
    <style>
      /* General Styles */
/* Base Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    background-color: #f9f9f9;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Header Styling */
header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    background-color: #fff;
    color: #333;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: background-color 0.3s ease;
}

.logo-img {
    height: 50px;
}

/* Navigation Styling */
nav {
    display: flex;
    align-items: center;
    margin-left: auto;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 25px;
    margin: 0;
    padding: 0;
}

nav ul li a {
    color: #333;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav ul li a:hover {
    color: #007bff;
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #007bff;
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 14px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-left: 20px;
}

.dark-mode-toggle:hover {
    background-color: #0056b3;
}

/* Posts Container */
.posts-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.post {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

.post h2 {
    font-size: 1.5em;
    color: #003366;
}

.post p {
    font-size: 1rem;
    line-height: 1.6;
    color: #000;
}

/* Like Button */
.like-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.like-button:hover {
    background-color: #0056b3;
}

footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: white;
    margin-top: 40px;
}

/* Common Dark Mode Styles */
body.dark-mode {
    background-color: #1b1b1b;
    color: #e0e0e0;
}

body.dark-mode header,
body.dark-mode footer {
    background-color: #2a2a2a;
    color: #f0f0f0;
}

body.dark-mode nav ul li a {
    color: #e0e0e0;
}

body.dark-mode nav ul li a:hover {
    color: #4db8ff;
}

body.dark-mode .posts-container,
body.dark-mode .post {
    background-color: #2b2b2b;
    border-color: #4db8ff;
}

body.dark-mode .post h2 {
    color: #4db8ff;
}

body.dark-mode .post p {
    color: #e0e0e0;
}

body.dark-mode .like-button {
    background-color: #4db8ff;
    color: #1b1b1b;
}

body.dark-mode .like-button:hover {
    background-color: #007bff;
    color: #ffffff;
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
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="create_post.php">Create Post</a></li>
            <li><a href="logout.php">Logout   </a></li>
        </ul>
        <button id="darkModeToggle" class="dark-mode-toggle">🌙 Dark Mode</button>
    </nav>
</header>


<section class="posts-container">
<h1> Feed</h1>
    <?php while ($post = $posts->fetch_assoc()): ?>
        <div class="post">
            <h2><?php echo htmlspecialchars($post['full_name']); ?> - <?php echo htmlspecialchars($post['post_title']); ?></h2>
            <p><?php echo htmlspecialchars($post['post_content']); ?></p>
            <p><small>Posted on: <?php echo htmlspecialchars($post['created_at']); ?></small></p>
            <form action="all_posts.php" method="POST">
                <input type="hidden" name="like_post_id" value="<?php echo $post['post_id']; ?>">
                <button type="submit" class="like-button" 
                        <?php echo $post['user_liked'] > 0 ? 'disabled' : ''; ?>>
                    <?php echo $post['user_liked'] > 0 ? 'Liked' : 'Like'; ?>
                </button>
                <span><?php echo $post['like_count']; ?> Likes</span>
            </form>
        </div>
    <?php endwhile; ?>
</section>

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
        
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('click', toggleDarkMode);
    });
</script>

</body>
</html>
