
<?php
session_start();
include 'db.php'; // Ensures your database connection file is loaded
// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo "No user session set.";
    header("Location: login.php");
    exit;
}

// Fetch user's enrolled courses
// Fetch user's enrolled courses using the enrollments table
$userId = $_SESSION['user'];
$courses = [];


// Handle Like Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postId'])) {
    $userId = $_SESSION['user'];
    $postId = $_POST['postId'];

    // Check if the user has already liked the post
    $checkLikeQuery = $conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
    $checkLikeQuery->bind_param("ii", $postId, $userId);
    $checkLikeQuery->execute();
    $likeResult = $checkLikeQuery->get_result();

    if ($likeResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Already liked']);
    } else {
        // Insert the like record into the database
        $likeQuery = $conn->prepare("INSERT INTO likes (post_id, user_id, liked_at) VALUES (?, ?, NOW())");
        $likeQuery->bind_param("ii", $postId, $userId);
        if ($likeQuery->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Liked successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
        }
        $likeQuery->close();
    }

    $checkLikeQuery->close();
    exit; // Ensure no further output after handling the AJAX request
}

// First, get the course IDs from the enrollments table
$enrollmentsQuery = $conn->prepare("
    SELECT Courses.course_id, Courses.title AS course_title, Enrollments.progress_percentage, 
           CourseMaterials.title AS material_title, CourseMaterials.file_path
    FROM Enrollments
    JOIN Courses ON Enrollments.course_id = Courses.course_id
    LEFT JOIN CourseMaterials ON Courses.course_id = CourseMaterials.course_id
    WHERE Enrollments.user_id = ?
    ORDER BY Courses.created_at DESC;
");
$enrollmentsQuery->bind_param("s", $userId);
$enrollmentsQuery->execute();
$enrollmentResult = $enrollmentsQuery->get_result();

// Store the course IDs in an array
$courseIds = [];
while ($row = $enrollmentResult->fetch_assoc()) {
    $courseId = $row['course_id'];
    if (!isset($courses[$courseId])) {
        $courses[$courseId] = [
            'title' => $row['course_title'],
            'progress' => $row['progress_percentage'],
            'materials' => []
        ];
    }
    if ($row['material_title'] && $row['file_path']) {
        $courses[$courseId]['materials'][] = [
            'title' => $row['material_title'],
            'file_path' => $row['file_path']
        ];
    }
}

$enrollmentsQuery->close();

// Now, if there are any course IDs, fetch the course details
if (!empty($courseIds)) {
    $idsPlaceholder = implode(',', array_fill(0, count($courseIds), '?'));
    $coursesQuery = $conn->prepare("SELECT title, progress FROM courses WHERE course_id IN ($idsPlaceholder)");
    
    // Bind parameters dynamically
    $types = str_repeat('s', count($courseIds)); // Assuming course_id is a string
    $coursesQuery->bind_param($types, ...$courseIds);
    $coursesQuery->execute();
    $result = $coursesQuery->get_result();

    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    $coursesQuery->close();
}
// Fetch user's posts
$posts = [];
$postsQuery = $conn->prepare("SELECT post_id, post_title, post_content FROM posts WHERE user_id = ?");
$postsQuery->bind_param("s", $userId);
$postsQuery->execute();
$result = $postsQuery->get_result();

while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

$postsQuery->close();

// Fetch like counts for each post
$likesCount = [];
$likesQuery = $conn->prepare("SELECT post_id, COUNT(*) as count FROM likes GROUP BY post_id");
$likesQuery->execute();
$likesResult = $likesQuery->get_result();

while ($row = $likesResult->fetch_assoc()) {
    $likesCount[$row['post_id']] = $row['count'];
}

$likesQuery->close();


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Dashboard</title>
    <style>
         /* General Styling */
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F0F2F5; /* Light mode background */
            color: #000; /* Light mode text color */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff; /* Light mode header background */
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
            color: #000; /* Light mode link color */
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #007bff; /* Light mode link hover color */
        }

        /* Dark Mode Button Styling */
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

        main {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: white; /* Light mode background */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-buttons {
            text-align: center;
            margin-bottom: 40px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Section and Heading Styles */
.courses-section {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.courses-section h2 {
    font-size: 1.8em;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.course {
    padding: 15px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: box-shadow 0.3s;
}

.course h3 {
    font-size: 1.5em;
    color: #0073e6;
    margin: 0 0 10px;
}

/* Progress Bar Styling */
.progress-bar {
    position: relative;
    height: 10px;
    background-color: #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background-color: #0073e6;
    border-radius: 5px;
    transition: width 0.4s ease;
}

.progress-bar p {
    font-size: 0.9em;
    color: #555;
}

/* Materials Section Styling */
.course h4 {
    font-size: 1.2em;
    color: #555;
    margin-top: 15px;
}

.course ul {
    list-style-type: none;
    padding: 0;
}

.course ul li {
    font-size: 1em;
    margin: 5px 0;
}

.course ul li a {
    color: #0073e6;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.course ul li a:hover {
    color: #005bb5;
}

.course p {
    font-size: 1em;
    color: #666;
}

/* Hover and Responsive Styling */
.course:hover {
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
}

@media (max-width: 600px) {
    .courses-section {
        padding: 15px;
    }
    
    .course {
        padding: 10px;
    }

    .course h3 {
        font-size: 1.3em;
    }
    
    .course h4 {
        font-size: 1.1em;
    }
}


        /* Connections Section */
        .connections-section {
            margin-bottom: 40px;
        }

        .connections-list {
            display: flex;
            gap: 20px;
        }

        .connection {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            width: 150px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .connection-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

       /* Posts Section Styling */
.posts-section {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.posts-section h2 {
    font-size: 1.8em;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.post {
    padding: 15px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: box-shadow 0.3s;
}

.post h3 {
    font-size: 1.5em;
    color: #0073e6;
    margin: 0 0 10px;
}

.post p {
    font-size: 1em;
    color: #666;
    margin: 10px 0;
}

/* Dark Mode Styles */
body.dark-mode .posts-section {
    background-color: #333;
}

body.dark-mode .posts-section h2 {
    color: #f0f0f0;
}

body.dark-mode .post {
    background-color: #444;
    border-color: #666;
}

body.dark-mode .post h3 {
    color: #66aaff;
}

body.dark-mode .post p {
    color: #ddd;
}

/* Hover Effect */
.post:hover {
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
}

/* Responsive Styling */
@media (max-width: 600px) {
    .posts-section {
        padding: 15px;
    }

    .post {
        padding: 10px;
    }

    .post h3 {
        font-size: 1.3em;
    }
}


        /* Footer Styling */
        footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

   /* Dark Mode Styles */
body.dark-mode {
    background-color: #121212; /* Darker background for a sleek appearance */
    color: #e0e0e0; /* Light gray text for readability */
}

/* Header */
body.dark-mode header {
    background-color: #1f1f1f; /* Darker gray header */
}

/* Navigation Links */
body.dark-mode nav a {
    color: #e0e0e0; /* Light gray links for visibility */
}

body.dark-mode nav a:hover {
    color: #90caf9; /* Light blue for hover to enhance visibility */
}

/* Dark Mode Toggle Button */
body.dark-mode .dark-mode-toggle {
    background-color: #90caf9; /* Softer blue button for visibility */
    color: #121212; /* Dark text on button for contrast */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #60a4e2; /* Slightly darker blue on hover */
    color: #ffffff; /* White text on hover for clarity */
}

/* Main Content */
body.dark-mode main {
    background-color: #2a2a2a; /* Softer dark gray for main content */
    color: #e0e0e0; /* Consistent text color */
}

/* Dark Mode for Courses Section */
body.dark-mode .courses-section {
    background-color: #2e2e2e;
    color: #dcdcdc;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
}

/* Dark Mode for Individual Course Blocks */
body.dark-mode .course {
    background-color: #3a3a3a; /* Dark background for the course blocks */
    border: 1px solid #555; /* Border to match the dark theme */
    color: #dcdcdc; /* Light text for readability */
}

/* Dark Mode for Course Titles and Materials */
body.dark-mode .course h3 {
    color: #66aaff;
}

body.dark-mode .course h4,
body.dark-mode .course p,
body.dark-mode .progress-bar p {
    color: #dcdcdc;
}

/* Dark Mode for Progress Bar */
body.dark-mode .progress-bar {
    background-color: #444;
}

body.dark-mode .progress-fill {
    background-color: #66aaff;
}

/* Dark Mode for Links in Course Materials */
body.dark-mode .course ul li a {
    color: #66aaff;
}

body.dark-mode .course ul li a:hover {
    color: #88ccff;
}
body.dark-mode .courses-section h2 {
    color: #dcdcdc; /* Light color for the heading text */
}


/* Connection Section */
body.dark-mode .connection {
    background-color: #333; /* Darker gray for connections section */
}

/* Post Section */
/* body.dark-mode .post {
    background-color: #333; /* Darker gray for posts */
    color: #e0e0e0; /* Consistent light text color */
} */

/* Footer */
body.dark-mode footer {
    background-color: #1f1f1f; /* Dark gray footer */
    color: #e0e0e0; /* Light text for footer */
}

/* Transitioning for smooth dark mode */
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
            <a href="courses.php">Courses</a>
            <a href="all_posts.php">Feed</a>
            <a href="create_post.php">Create Post</a>
            <a href="messages.php">Messages</a>
            <a href="connections.php">Connections</a>
            <a href="profile.php">Profile</a>
            <a href="notifications.php">Notifications</a>
            <a href="logout.php">Logout</a>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        </nav>
    </header>

    <main>
        <h1>Your Dashboard</h1>

        <div class="dashboard-buttons">
            <a href="skills_form.php" class="btn">Add Your Skills</a>
            <a href="recruitment.php" class="btn">Recruitment Needs</a>
        </div>

        <section class="courses-section">
    <h2>Enrolled Courses</h2>
    <?php foreach ($courses as $course): ?>
        <div class="course">
            <h3>Course Title: <?php echo htmlspecialchars($course['title']); ?></h3>
            
            <!-- <?php if (isset($course['progress'])): ?>
                <p>Progress:</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo htmlspecialchars($course['progress']); ?>%;"></div>
                </div>
                <p><?php echo htmlspecialchars($course['progress']); ?>% completed</p>
            <?php endif; ?> -->

            <?php if (!empty($course['materials'])): ?>
                <h4>Materials:</h4>
                <ul>
                    <?php foreach ($course['materials'] as $material): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank">
                                <?php echo htmlspecialchars($material['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No materials available for this course.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>


        <section class="posts-section">
    <h2>Your Posts</h2>
    <?php foreach ($posts as $post): ?>
        <div id="post-<?php echo $post['post_id']; ?>" class="post">
            <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
            <p><?php echo htmlspecialchars($post['post_content']); ?></p>
            <button class="like-btn" onclick="handleLike(<?php echo $post['post_id']; ?>)">Like</button>
            <span class="like-count"><?php echo isset($likesCount[$post['post_id']]) ? htmlspecialchars($likesCount[$post['post_id']]) : 0; ?> Likes</span>
        </div>
    <?php endforeach; ?>
</section>

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

        function handleLike(postId) {
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `postId=${postId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Update the like count display
            const likeCountElement = document.querySelector(`#post-${postId} .like-count`);
            let currentCount = parseInt(likeCountElement.textContent) || 0;
            likeCountElement.textContent = `${++currentCount} Likes`;
        } else if (data.status === 'error') {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}


// Load initial like states
window.onload = function() {
    const likedPosts = JSON.parse(localStorage.getItem('likedPosts')) || [];
    likedPosts.forEach(postId => {
        const likeCountElement = document.querySelector(`#${postId} .like-count`);
        if (likeCountElement) {
            let currentCount = parseInt(likeCountElement.textContent) || 0;
            likeCountElement.textContent = `${currentCount} Likes`;
        }
    });
};

    </script>
</body>

</html>