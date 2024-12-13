
<?php
session_start();
include 'db.php';
$error = "";  // To capture any error during registration

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollment = $_POST['loginEnrollment'];
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Check if user exists in the database
    $sql = "SELECT * FROM users WHERE enrollment = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $enrollment, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password

        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user'] = $row['user_id']; // Assuming 'user_id' is the unique ID in 'users' table
            header("Location: dashboard.php");
            exit;
        }else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "User not found. Please check your enrollment number and email.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Login</title>
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F9F9F9; /* Light background */
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: background-color 0.5s ease, color 0.5s ease;
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: #1c1c1c; /* Darker background */
    color: #f5f5f5; /* Light text color */
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #fff; /* Light header background */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: background-color 0.5s ease, color 0.5s ease;
}

body.dark-mode header {
    background-color: #2a2a2a; /* Dark header background */
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
    color: #000; /* Default link color */
    text-decoration: none;
    margin-left: 25px;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav a:hover {
    color: #007bff; /* Link hover color */
}

body.dark-mode nav a {
    color: #f5f5f5; /* Light link color in dark mode */
}

body.dark-mode nav a:hover {
    color: #90caf9; /* Light blue on hover in dark mode */
}

/* Dark Mode Toggle Button */
.dark-mode-toggle {
    background-color: #007bff; /* Default button background */
    color: white; /* Default button text */
    padding: 6px 12px; /* Smaller padding */
    border: none;
    border-radius: 50px; /* Oval button */
    cursor: pointer;
    font-size: 14px;
    margin-left: 25px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode .dark-mode-toggle {
    background-color: #90caf9; /* Button background in dark mode */
    color: #1c1c1c; /* Dark text on button */
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #60a4e2; /* Darker blue on hover */
}

/* Main Layout */
main {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1;
    padding: 40px 20px;
    transition: background-color 0.5s ease, color 0.5s ease;
}

/* Form Container */
.form-container {
    background-color: white; /* Light form background */
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 40px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    transition: background-color 0.5s ease, color 0.5s ease;
}

body.dark-mode .form-container {
    background-color: #2a2a2a; /* Dark form background */
    border: 1px solid #90caf9; /* Light blue border */
}

/* Form Labels */
.form-container label {
    display: block;
    margin-bottom: 5px;
    text-align: left;
}

body.dark-mode .form-container label {
    color: #f5f5f5; /* Light label text */
}

/* Input Fields */
.form-container input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc; /* Default border color */
    border-radius: 4px;
}

body.dark-mode .form-container input {
    background-color: #333; /* Dark input background */
    color: #fff; /* Light text color */
    border: 1px solid #90caf9; /* Light blue border */
}

/* Button */
.form-container button {
    background-color: #007bff; /* Default button background */
    color: white; /* Default button text */
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    transition: background-color 0.3s ease;
}

.form-container button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

body.dark-mode .form-container button {
    background-color: #90caf9; /* Light blue button in dark mode */
    color: #1c1c1c; /* Dark button text in dark mode */
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333; /* Dark footer background */
    color: white; /* Default footer text */
    width: 100%;
    transition: background-color 0.5s ease, color 0.5s ease;
}

body.dark-mode footer {
    background-color: #2a2a2a; /* Darker footer background in dark mode */
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
            <a href="about.php">About</a>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        </nav>
    </header>

    <main>
        <h1>Login to SkillHub</h1>
        <div class="form-container">
            <form id="loginForm" action="login.php" method="post" onsubmit="return validateLoginForm()">
                <label for="loginEnrollment">Enrollment Number</label>
                <input type="text" id="loginEnrollment" name="loginEnrollment" placeholder="Enrollment Number" required pattern="\d{8}" title="Please enter a valid 8-digit Enrollment Number">
                <label for="loginEmail">Official JIIT Email</label>
                <input type="email" id="loginEmail" name="loginEmail" placeholder="example@mail.jiit.ac.in" required pattern=".+@mail\.jiit\.ac\.in" title="Please use your official JIIT email">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" required minlength="6">
                <button type="submit">Login</button>
                <?php if ($error): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
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

   function validateLoginForm() {
        const enrollment = document.getElementById("loginEnrollment").value;
        const email = document.getElementById("loginEmail").value;
        const password = document.getElementById("loginPassword").value;

        // Get the current year
        const currentYear = new Date().getFullYear() % 100; // Get last two digits of the current year

        // Check Enrollment Number (first two digits must signify the admission year and should be valid)
        const enrollmentPattern = /^\d{8}$/;
        const admissionYear = parseInt(enrollment.substring(0, 2), 10);

        if (!enrollmentPattern.test(enrollment) || admissionYear > currentYear) {
            alert("Please enter a valid 8-digit Enrollment Number with the first two digits indicating a valid admission year.");
            return false;
        }

        // Check JIIT Email (must be in the format example@mail.jiit.ac.in)
        const emailPattern = /^[a-zA-Z0-9._%+-]+@mail\.jiit\.ac\.in$/;
        if (!emailPattern.test(email)) {
            alert("Please enter your official JIIT email (e.g., example@mail.jiit.ac.in).");
            return false;
        }

        // Check Password (must be at least 6 characters)
        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return false;
        }

        // If all validations pass
        return true;
    }

    </script>
</body>
</html>