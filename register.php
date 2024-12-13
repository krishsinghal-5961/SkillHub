
<?php
include 'db.php';
$error = "";  // To capture any error during registration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $enrollment = $_POST['enrollment'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $skills = $_POST['skills'];
    $profilePhoto = $_FILES['profilePhoto'];

    // File upload
    $uploadDir = "";
    $photoPath = $uploadDir . basename($profilePhoto["name"]);
    if (!move_uploaded_file($profilePhoto["tmp_name"], $photoPath)) {
        $error = "Failed to upload profile photo.";
    }

    // Insert user data
    $sql = "INSERT INTO Users (full_name, enrollment, email, password_hash, profile_image) 
            VALUES ('$fullName', '$enrollment', '$email', '$password', '$photoPath')";

    if ($conn->query($sql) === TRUE) {
        $userId = $conn->insert_id;
        
        // Insert skills
        $skillList = explode(',', $skills);
        foreach ($skillList as $skill) {
            $skill = trim($skill);
            $conn->query("INSERT INTO Skills (user_id, skill_name) VALUES ('$userId', '$skill')");
        }
        
        // Redirect on success
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #000;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark mode styles */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
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

        body.dark-mode header {
            background-color: #1f1f1f;
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

        body.dark-mode nav a {
            color: #e0e0e0;
        }

        /* Dark mode toggle button */
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
            max-width: 600px;
            margin: 30px auto;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.dark-mode main {
            background-color: #2a2a2a;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        body.dark-mode h1 {
            color: #80b3ff;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        body.dark-mode .form-container label {
            color: #e0e0e0;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        body.dark-mode .form-container input,
        body.dark-mode .form-container select {
            background-color: #3a3a3a;
            color: #e0e0e0;
            border: 1px solid #555;
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
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.dark-mode footer {
            background-color: #1a1a1a;
        }

        /* Password strength bar */
        progress {
            width: 100%;
            height: 10px;
            margin: 10px 0;
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
        <a href="login.php">Login</a>
        <a href="about.php">About</a>
        <button id="darkModeToggle" class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>

<main>
    <h1>Register for SkillHub</h1>
    <div class="form-container">
        <?php if ($error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form id="registerForm" action="register.php" method="post" enctype="multipart/form-data" onsubmit="return validateRegisterForm()">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" placeholder="Full Name" required>

            <label for="enrollment">Enrollment Number</label>
            <input type="text" id="enrollment" name="enrollment" placeholder="Enrollment Number" required pattern="\d{8}" title="Please enter a valid 8-digit Enrollment Number">

            <label for="email">Official JIIT Email</label>
            <input type="email" id="email" name="email" placeholder="example@mail.jiit.ac.in" required pattern=".+@mail\.jiit\.ac\.in" title="Please use your official JIIT email address">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password must be of atleast 6 characters)" required minlength="6">
            <progress id="strengthBar" max="4" value="0"></progress>
            <span id="strengthText">Weak</span>

            <label for="profilePhoto">Profile Photo (only.jpg/.jpeg files)</label>
            <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" required>

            <label for="skills">Skills (comma-separated)</label>
            <input type="text" id="skills" name="skills" placeholder="e.g., JavaScript, Python, C++" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub | Made with ❤️ by the SkillHub Team</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
</footer>

<script>
    function validateRegisterForm() {
        let fullName = document.getElementById("fullName").value;
        let enrollment = document.getElementById("enrollment").value;
        let email = document.getElementById("email").value;
        let skills = document.getElementById("skills").value;
        let profilePhoto = document.getElementById("profilePhoto").value;

        if (!fullName || !enrollment || !email || !skills || !profilePhoto) {
            alert("Please fill in all fields.");
            return false;
        }
        return true;
    }

    document.getElementById("password").addEventListener("input", function () {
        const password = this.value;
        const strengthBar = document.getElementById("strengthBar");
        const strengthText = document.getElementById("strengthText");
        
        let strength = 0;
        
        if (password.length > 5) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        strengthBar.value = strength;
        
        const strengthLabels = ['Weak', 'Fair', 'Good', 'Strong','Very Strong'];
        strengthText.textContent = strengthLabels[strength];
    });

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