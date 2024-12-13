<?php
// view_profile.php

// Include the database connection
include('db.php');

// Get the user_id from the URL parameter
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Fetch user details
$user_query = $conn->prepare("SELECT full_name, enrollment, email, profile_image FROM Users WHERE user_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Fetch user skills
$skills_query = $conn->prepare("SELECT skill_name FROM Skills WHERE user_id = ?");
$skills_query->bind_param("i", $user_id);
$skills_query->execute();
$skills_result = $skills_query->get_result();
$skills = [];
while ($row = $skills_result->fetch_assoc()) {
    $skills[] = $row['skill_name'];
}

// Fetch user projects
$projects_query = $conn->prepare("SELECT project_title, project_description FROM Projects WHERE user_id = ?");
$projects_query->bind_param("i", $user_id);
$projects_query->execute();
$projects_result = $projects_query->get_result();
$projects = [];
while ($row = $projects_result->fetch_assoc()) {
    $projects[] = $row;
}

// Fetch courses the user is enrolled in
$courses_query = $conn->prepare("
    SELECT c.title, c.description 
    FROM Enrollments AS e
    INNER JOIN Courses AS c ON e.course_id = c.course_id
    WHERE e.user_id = ?
");
$courses_query->bind_param("i", $user_id);
$courses_query->execute();
$courses_result = $courses_query->get_result();
$courses = [];
while ($row = $courses_result->fetch_assoc()) {
    $courses[] = $row;
}


// Fetch user posts
$posts_query = $conn->prepare("SELECT post_title, post_content FROM Posts WHERE user_id = ?");
$posts_query->bind_param("i", $user_id);
$posts_query->execute();
$posts_result = $posts_query->get_result();
$posts = [];
while ($row = $posts_result->fetch_assoc()) {
    $posts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Profile of <?php echo htmlspecialchars($user['full_name']); ?></title>
        <style>
        /* General Styles */
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
            max-width: 1200px;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Profile Card */
        .profile-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-card h2 {
            margin: 10px 0;
            font-size: 24px;
            color: black;
        }

        .profile-card p {
            color: #666;
            font-size: 0.9rem;
        }

        /* Profile Info and Other Sections */
        .profile-info,
        .skills-section,
        .projects-section,
        .connections-section {
            background-color: white;
            border: 2px solid #007bff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .profile-info h2,
        .skills-section h2,
        .projects-section h2,
        .connections-section h2 {
            font-size: 1.5rem;
            color: #000;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        /* Skills Section */
        .skills-section ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .skills-section li {
            display: inline-block;
            background-color: blue;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            margin: 0 10px 10px 0;
            font-size: 0.9rem;
        }

        .skills-section button {
            background-color: #FF6347;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .skills-section button:hover {
            background-color: #FF4500;
        }

        /* Projects and Connections */
        .projects-section,
        .connections-section {
            margin-top: 20px;
        }

        .connections-section .connections-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .connection {
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
        }

        .connection img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .connection p {
            margin: 0;
            font-size: 1rem;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .connect-btn {
            margin-left: 10px;
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
        /* Light Mode - Projects Section */
.projects-section {
    background-color: white;
    border: 2px solid #007bff;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    width: 100%;
    max-width: 800px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.projects-section h2 {
    font-size: 1.5rem;
    color: #000;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

/* Dark Mode - Projects Section */
body.dark-mode .projects-section {
    background-color: #333333; /* Darker background for project section */
    border-color: #81d4fa; /* Light blue border to match theme */
    color: #e0e0e0; /* Softer text color */
}

body.dark-mode .projects-section h2 {
    color: #e0e0e0; /* Consistent text color */
    border-bottom-color: #81d4fa; /* Light blue border for heading */
}


/* Light Mode - Courses Section */
.courses-section {
    background-color: white;
    border: 2px solid #007bff;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    width: 100%;
    max-width: 800px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.courses-section h2 {
    font-size: 1.5rem;
    color: #000;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

/* Dark Mode - Courses Section */
body.dark-mode .courses-section {
    background-color: #333333; /* Darker background for courses section */
    border-color: #81d4fa; /* Light blue border to match theme */
    color: #e0e0e0; /* Softer text color */
}

body.dark-mode .courses-section h2 {
    color: #e0e0e0; /* Consistent text color */
    border-bottom-color: #81d4fa; /* Light blue border for heading */
}

        
       /* Dark Mode Styles */
body.dark-mode {
    background-color: #1e1e1e; /* Darker background for smoother look */
    color: #e0e0e0; /* Softer white for less strain on the eyes */
}

body.dark-mode header {
    background-color: #2a2a2a; /* Slightly darker header */
}

body.dark-mode nav a {
    color: #e0e0e0; /* Consistent link color */
}

body.dark-mode nav a:hover {
    color: #81d4fa; /* Light blue for hover effect */
}

body.dark-mode .dark-mode-toggle {
    background-color: #81d4fa; /* Theme lighter blue */
    color: #1e1e1e; /* Dark background for button */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #4fc3f7; /* Deeper blue on hover */
    color: white; /* White text for visibility */
}

body.dark-mode .profile-card {
    background-color: #333333; /* Moderate background for profile card */
    color: #e0e0e0; /* Consistent text color */
}

body.dark-mode .profile-info,
body.dark-mode .skills-section,
body.dark-mode .projects-section,
body.dark-mode .connections-section {
    background-color: #333333; /* Moderate shade for sections */
    border-color: #81d4fa; /* Border color matching theme */
    color: #e0e0e0; /* Consistent text color */
}

body.dark-mode .profile-info h2,
body.dark-mode .profile-card h2,
body.dark-mode .skills-section h2,
body.dark-mode .projects-section h2,
body.dark-mode .connections-section h2 {
    color: #e0e0e0; /* Consistent heading color */
    border-bottom-color: #81d4fa; /* Light blue for bottom border */
}

body.dark-mode .profile-card p {
    color: #b0bec5; /* Softer gray for paragraph text */
    font-size: 0.9rem;
}

body.dark-mode .skills-section li {
    background-color: #00008B; /* Blue for skill list items */
    color: white; /* Darker background for better contrast */
}

body.dark-mode .skills-section button {
    background-color: #ff6347; /* Themed red-orange for button */
    color: #e0e0e0;
}

body.dark-mode .skills-section button:hover {
    background-color: #ff4500; /* Darker red on hover */
    color: #e0e0e0;
}

body.dark-mode .btn {
    background-color: #81d4fa; /* Theme lighter blue */
    color: #1e1e1e; /* Darker background */
}

body.dark-mode .btn:hover {
    background-color: #4fc3f7; /* Deeper blue on hover */
    color: #e0e0e0; /* Softer text color */
}

body.dark-mode footer {
    background-color: #2a2a2a; /* Consistent footer background */
    color: #e0e0e0; /* Footer text */
}

/* Posts Section */
.posts-section {
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    width: 100%;
    max-width: 800px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.posts-section h2 {
    font-size: 1.5rem;
    color: #000;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

/* Dark Mode - Posts Section */
body.dark-mode .posts-section {
    background-color: #333333;
    border-color: #81d4fa;
    color: #e0e0e0;
}

body.dark-mode .posts-section h2 {
    color: #e0e0e0;
    border-bottom-color: #81d4fa;
}

/* Post Box */
.post-box {
    background-color: white;
    border: 2px solid #007bff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    padding: 15px;
    transition: transform 0.3s ease-in-out;
    position: relative;
}

body.dark-mode .post-box {
    background-color: #444444;
    border-color: #81d4fa;
}

.post-box:hover {
    transform: translateY(-5px);
}

/* Post Title */
.post-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

body.dark-mode .post-title {
    color: #e0e0e0;
}

/* Post Description */
.post-description {
    font-size: 0.9rem;
    color: #666;
}

body.dark-mode .post-description {
    color: #b0bec5;
}

/* Margin around posts */
.posts-section ul {
    list-style: none;
    padding: 0;
    counter-reset: post-counter; /* Initialize counter */
}

.posts-section li {
    margin-bottom: 20px;
    position: relative;
    padding-left: 35px; /* Add padding to the left to accommodate the number */
}

.posts-section li::before {
    content: counter(post-counter) ". "; /* Add numbering before each post */
    counter-increment: post-counter; /* Increment counter for each post */
    font-weight: bold;
    font-size: 1.1rem;
    color: #007bff; /* Blue color for numbering */
    position: absolute;
    top: 10px;
    left: 10px; /* Move numbering inside the post box */
}

/* Dark Mode - Post Numbering */
body.dark-mode .posts-section li::before {
    color: #81d4fa; /* Light blue for numbering in dark mode */
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
        <div class="profile-card">
            <img src="<?php echo htmlspecialchars($user['profile_image'] ? $user['profile_image'] : 'default.jpg'); ?>" alt="Profile Image">
            <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
            <p>Enrollment Number: <?php echo htmlspecialchars($user['enrollment']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="skills-section">
            <h2>Skills</h2>
            <ul>
                <?php foreach ($skills as $skill): ?>
                    <li><?php echo htmlspecialchars($skill); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="projects-section">
            <h2>Projects</h2>
            <ul>
                <?php foreach ($projects as $project): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($project['project_title']); ?></strong>
                        <p><?php echo htmlspecialchars($project['project_description']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="courses-section">
            <h2>Enrolled Courses</h2>
            <ul>
                <?php foreach ($courses as $course): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                        <p><?php echo htmlspecialchars($course['description']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="posts-section">
            <h2>Posts</h2>
            <ul>
                <?php foreach ($posts as $post): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($post['post_title']); ?></strong>
                        <p><?php echo htmlspecialchars($post['post_content']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>

    <script>
        // Dark mode functionality
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

        window.addEventListener("load", function () {
            const darkModePreference = localStorage.getItem('darkMode');
            const darkModeToggle = document.querySelector(".dark-mode-toggle");

            if (darkModePreference === 'enabled') {
                document.body.classList.add('dark-mode');
                darkModeToggle.textContent = "🌞 Light Mode";
            } else {
                darkModeToggle.textContent = "🌙 Dark Mode";
            }
        });
    </script>
</body>
</html>
