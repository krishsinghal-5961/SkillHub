<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - SkillHub</title>
    <style>
      /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F9F9F9;
    color: #333;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: #1e1e1e; /* Darker background for smoother look */
    color: #e0e0e0; /* Softer white for less strain on the eyes */
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
    transition: background-color 0.3s ease;
}

body.dark-mode header {
    background-color: #2a2a2a; /* Slightly darker header */
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

body.dark-mode nav a {
    color: #e0e0e0; /* Consistent link color */
}

nav a:hover {
    color: #81d4fa; /* Light blue for hover effect */
}

/* Main Content */
main {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode main {
    background-color: #333333; /* Moderate shade for content */
}

main h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: blue;
    text-align: center;
}

body.dark-mode main h1 {
    color: #81d4fa; /* Theme light blue */
}

main h2 {
    font-size: 1.75rem;
    margin-top: 30px;
    color: #333;
}

body.dark-mode main h2 {
    color: #e0e0e0; /* Consistent heading color */
}

main p {
    font-size: 1rem;
    line-height: 1.6;
    margin: 15px 0;
}

body.dark-mode main p {
    color: #b0bec5; /* Softer gray for paragraph text */
}

main a {
    color: #81d4fa; /* Theme blue for links */
    text-decoration: underline;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: #fff;
    margin-top: 40px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode footer {
    background-color: #2a2a2a; /* Consistent with header */
    color: #e0e0e0; /* Consistent text color */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #81d4fa; /* Fresh, lighter blue */
    color: #1e1e1e; /* Dark background for button */
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dark-mode-toggle:hover {
    background-color: #4fc3f7; /* Deeper blue on hover */
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
            <a href="about.php">About</a>
            <a href="terms.php">Terms & Conditions</a>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        </nav>
    </header>

    <main>
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us. At SkillHub, we collect personal information such as your name and email when you register. We also gather usage data to improve our services.</p>
        <h2>Data Usage</h2>
        <p>Your information helps us personalize your experience, communicate updates, and enhance our platform.</p>
        <h2>Security</h2>
        <p>We implement security measures to protect your data, but no method is completely secure.</p>
        <h2>Contact</h2>
        <p>For questions, email us at <a href="mailto:support@skillhub.com">support@skillhub.com</a>.</p>
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

        // Check for saved user preference on page load
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
