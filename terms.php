<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - SkillHub</title>
    <style>
        /* General Styles */
        body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F9F9F9; /* Light mode background */
    color: #333; /* Light mode text color */
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: #1e1e1e; /* Darker background for dark mode */
    color: #e0e0e0; /* Softer text color for dark mode */
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #fff; /* Light mode header */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: background-color 0.3s ease;
}

body.dark-mode header {
    background-color: #2a2a2a; /* Dark mode header */
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

body.dark-mode nav a {
    color: #e0e0e0; /* Dark mode link color */
}

nav a:hover {
    color: #007bff; /* Light mode link hover color */
}

body.dark-mode nav a:hover {
    color: #81d4fa; /* Dark mode link hover color */
}

/* Main Content */
main {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff; /* Light mode main background */
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode main {
    background-color: #333333; /* Dark mode main background */
}

main h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: blue; /* Light mode h1 color */
    text-align: center;
}

body.dark-mode main h1 {
    color: #81d4fa; /* Dark mode h1 color */
}

main h2 {
    font-size: 1.75rem;
    margin-top: 30px;
    color: #333; /* Light mode h2 color */
}

body.dark-mode main h2 {
    color: #f0f0f0; /* Dark mode h2 color */
}

main p {
    font-size: 1rem;
    line-height: 1.6;
    margin: 15px 0;
}

body.dark-mode main p {
    color: #b0bec5; /* Dark mode paragraph text color */
}

main a {
    color: blueviolet; /* Light mode link color */
    text-decoration: underline;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333; /* Light mode footer background */
    color: #fff; /* Light mode footer text color */
    margin-top: 40px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode footer {
    background-color: #2a2a2a; /* Dark mode footer background */
    color: #e0e0e0; /* Dark mode footer text color */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #81d4fa; /* Dark mode toggle button background */
    color: #1e1e1e; /* Dark mode toggle button text */
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode .dark-mode-toggle {
    background-color: #90caf9; /* Lighter background for the toggle in dark mode */
}

.dark-mode-toggle:hover {
    background-color: #60a4e2; /* Dark mode toggle button hover color */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #4fc3f7; /* Dark mode toggle button hover effect */
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
        <a href="privacy.php">Privacy Policy</a>
        <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>

<main>
    <h1>Terms of Service</h1>

    <p>Welcome to SkillHub! By using our platform, you agree to these terms. Please read them carefully to understand your rights and obligations.</p>

    <h2>Use of Services</h2>
    <p>You agree to use SkillHub legally and responsibly. You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer.</p>

    <h2>Intellectual Property</h2>
    <p>All content available on SkillHub, including but not limited to text, graphics, logos, and software, is owned by us and is protected by copyright and other intellectual property laws.</p>

    <h2>Liability</h2>
    <p>We are not liable for any indirect, incidental, or consequential damages resulting from your use of our platform. Use SkillHub at your own risk.</p>

    <h2>Contact Us</h2>
    <p>If you have any questions regarding these terms, please contact us via email at <a href="mailto:support@skillhub.com">support@skillhub.com</a>.</p>
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
