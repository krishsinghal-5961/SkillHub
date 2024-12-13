<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - About</title>
    <style>
       /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F9F9F9;
    color: #333;
}

/* Dark Mode Styles */
.dark-mode {
    background-color: #121212; /* Dark background */
    color: #f1f1f1; /* Light text */
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #fff; /* Light header */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.dark-mode header {
    background-color: #1e1e1e; /* Darker gray header for dark mode */
}

/* Logo */
.logo img {
    height: 50px;
}

/* Navigation */
nav {
    display: flex;
    align-items: center;
}

nav a {
    color: #000; /* Dark link color */
    text-decoration: none;
    margin-left: 25px;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.dark-mode nav a {
    color: white; /* Light blue links in dark mode */
}

nav a:hover {
    color: #007bff; /* Hover color for light mode */
}

.dark-mode nav a:hover {
    color: #4db8ff; /* Lighter blue hover color for dark mode */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #90caf9; /* Lighter blue for button */
    color: #121212; /* Dark background for text */
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dark-mode-toggle:hover {
    background-color: #4fc3f7; /* Darker blue on hover */
    color: #ffffff; /* White text on hover */
}

/* Main Content */
main {
    max-width: 1000px;
    margin: 40px auto;
    padding: 60px 100px;
    background-color: #fff; /* Light background for main content */
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.dark-mode main {
    background-color: #1e1e1e; /* Dark background for main */
    color: #f1f1f1; /* Light text for main */
}

main h1 {
    font-size: 3rem;
    font-weight: 800;
    color: blue; /* Blue title color */
    margin-bottom: 40px;
    text-align: center;
}

.dark-mode main h1 {
    color: #81d4fa; /* Soft blue title color for dark mode */
}

main h2 {
    font-size: 2rem;
    margin-top: 40px;
    color: #333; /* Dark color for headings */
}

.dark-mode main h2 {
    color: #f0f0f0; /* Light color for headings in dark mode */
}

main p, main ul li {
    font-size: 1.25rem;
    line-height: 1.8;
    margin: 20px 0;
}

.dark-mode main p, .dark-mode main ul li {
    color: #e0e0e0; /* Light text for paragraphs and list items */
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333; /* Dark footer background */
    color: #fff; /* Light footer text */
    margin-top: 40px;
}

.dark-mode footer {
    background-color: #2a2a2a; /* Slightly lighter footer background in dark mode */
    color: #e0e0e0; /* Softer white footer text */
}

    </style>
</head>

<body>

<header>
    <div class="logo">
        <a href="krish.php">
            <img src="logo.png" alt="SkillHub Logo" class="logo-img">
        </a>
    </div>
    <nav>
        <a href="krish.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>

<main>
    <h1>About SkillHub</h1>
    <section class="intro">
        <p>At SkillHub, we're passionate about creating a space where students at Jaypee Institute of Information Technology can connect, learn, and grow together. Our platform focuses on building real-world skills and helping students collaborate on meaningful projects. Whether you're looking to learn something new, share your expertise, or work with others, SkillHub is here to support you every step of the way.</p>
    </section>

    <section class="mission">
        <h2>Our Mission</h2>
        <p>We believe that learning shouldn’t be limited to classrooms. Our mission is to create a collaborative environment where students can not only develop technical skills but also form valuable connections that will help them in their careers. SkillHub is all about learning by doing, sharing knowledge, and growing together as a community.</p>
    </section>

    <section class="features">
        <h2>What We Offer</h2>
        <ul>
            <?php
                $features = [
                    "Peer Learning: Exchange skills with fellow students through interactive courses, projects, and workshops.",
                    "Hands-on Projects: Collaborate with others on real-world projects to gain practical experience.",
                    "Flexible Learning Paths: Personalize your learning journey to fit your goals and interests.",
                    "Mentorship & Guidance: Get advice and insights from industry professionals and mentors eager to help you succeed.",
                    "Community-Driven: Join a vibrant community of learners, innovators, and doers."
                ];
                foreach ($features as $feature) {
                    echo "<li><strong>$feature</strong></li>";
                }
            ?>
        </ul>
    </section>

    <section class="team">
        <h2>Meet Our Team</h2>
        <p>SkillHub is powered by a dedicated team of students who share a passion for learning and collaboration. Here’s a little more about the people behind the platform:</p>
        <ul>
            <?php
                $teamMembers = [
                    ["Krish Singhal", "Team Lead"],
                    ["Vaibhav Singh", "Frontend Developer"],
                    ["Jatin Yadav", "Backend Developer"],
                    ["Pritish Negi", "Q.A. and Code Debugging "]
                ];
                foreach ($teamMembers as $member) {
                    echo "<li><strong>{$member[0]} - {$member[1]}</strong></li>";
                }
            ?>
        </ul>
        <p>Together, we’re working hard to build a platform that makes learning easier and more accessible to everyone.</p>
    </section>

    <section class="contact">
        <h2>Get in Touch</h2>
        <p>If you have any questions or want to learn more about SkillHub, don’t hesitate to reach out. We’re always excited to hear from students and potential collaborators! You can drop us an email at <a href="mailto:<?php echo '23103373@mail.jiit.ac.in'; ?>">Team Lead</a>.</p>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub | Made with ❤️ by the SkillHub Team</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
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
