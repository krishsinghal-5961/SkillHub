<?php
// Include the database connection
include('db.php');

// Assuming the user ID is available through session or as a parameter
session_start();
$user_id = $_SESSION['user'] ?? 1; // Replace with actual session variable or dynamic ID

// Fetch user details
$user_query = $conn->prepare("SELECT full_name, enrollment, email FROM Users WHERE user_id = ?");
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

// Fetch user connections
$connections_query = $conn->prepare("SELECT u.full_name, u.profile_image FROM Connections c JOIN Users u ON c.connection_user_id = u.user_id WHERE c.user_id = ?");
$connections_query->bind_param("i", $user_id);
$connections_query->execute();
$connections_result = $connections_query->get_result();
$connections = [];
while ($row = $connections_result->fetch_assoc()) {
    $connections[] = $row;
}

// Fetch user details, including profile_image
$user_query = $conn->prepare("SELECT full_name, enrollment, email, profile_image FROM Users WHERE user_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Profile</title>
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
    <?php
    $imagePath = htmlspecialchars($user['profile_image'] ?: 'default.jpg');
    // echo "<p>Image Path: $imagePath</p>"; // Debugging line
    ?>
    <img src="<?php echo $imagePath; ?>" alt="Profile Image">
    <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
    <p>Enrollment Number: <?php echo htmlspecialchars($user['enrollment']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
</div>



        <div class="profile-info">
            <h2>Personal Information</h2>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Enrollment Number:</strong> <?php echo htmlspecialchars($user['enrollment']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="skills-section">
            <h2>Skills</h2>
            <ul id="skillsList">
                <?php foreach ($skills as $skill): ?>
                    <li><?php echo htmlspecialchars($skill); ?> </li>
                <?php endforeach; ?>
            </ul>
            <a href="skills_form.php" class="btn">Add a Skill</a>
        </div>

        <div class="projects-section">
            <h2>Projects</h2>
            <p>Project details will be displayed here.</p>
            <a href="projects.php" class="btn">View Projects</a>
        </div>

        <div class="connections-section">
            <h2>Your Connections</h2>
            <div class="connections-list">
                <?php foreach ($connections as $connection): ?>
                    <div class="connection">
                        <img src="<?php echo htmlspecialchars($connection['profile_image']); ?>" alt="Profile Image">
                        <p><?php echo htmlspecialchars($connection['full_name']); ?></p>
                        <!-- <button class="btn connect-btn">Message</button> -->
                    </div>
                <?php endforeach; ?>
            </div>
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
        
        function removeSkill(btn) {
            var li = btn.parentNode;
            li.parentNode.removeChild(li);
        }

        window.addEventListener("load", function () {
    // Dark mode initialization
    const darkModePreference = localStorage.getItem('darkMode');
    const darkModeToggle = document.querySelector(".dark-mode-toggle");

    if (darkModePreference === 'enabled') {
        document.body.classList.add('dark-mode');
        darkModeToggle.textContent = "🌞 Light Mode";
    } else {
        darkModeToggle.textContent = "🌙 Dark Mode";
    }

    // Update connection images from localStorage
    const connectionImages = document.querySelectorAll(".connections-list .connection img");
    connectionImages.forEach(img => {
        const userId = img.getAttribute("data-user-id"); // Assume you added this to each img in PHP
        const storedPhoto = localStorage.getItem(`photo_${userId}`);
        if (storedPhoto) {
            img.src = storedPhoto; // Update with photo from localStorage if it exists
        }
    });
});

// Listen for changes in localStorage and update images if needed
window.addEventListener('storage', (event) => {
    if (event.key.startsWith('photo_')) { // Check if the key corresponds to a photo update
        const userId = event.key.split('_')[1]; // Extract user ID
        const updatedPhoto = event.newValue;

        const connectionImg = document.querySelector(`.connections-list .connection img[data-user-id='${userId}']`);
        if (connectionImg) {
            connectionImg.src = updatedPhoto; // Update the image src if it's on the page
        }
    }
});

    </script>
</body>
</html>
