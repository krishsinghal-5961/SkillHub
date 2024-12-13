<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Logout</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            height: 100vh;
            justify-content: space-between;
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

        /* Main Layout */
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            text-align: center;
            padding: 20px;
        }

        /* Button Styles */
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            width: 100%;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #1c1c1c;
            color: #e0e0e0;
        }

        body.dark-mode header {
            background-color: #2a2a2a;
        }

        body.dark-mode nav a {
            color: #e0e0e0;
        }

        body.dark-mode nav a:hover {
            color: #90caf9;
        }

        body.dark-mode .btn {
            background-color: #90caf9;
            color: #1c1c1c;
        }

        body.dark-mode .btn:hover {
            background-color: #60a4e2;
            color: white;
        }

        body.dark-mode footer {
            background-color: #2a2a2a;
            color: #e0e0e0;
        }

    </style>
</head>
<body>
    <?php
        session_start();
        session_unset();
        session_destroy();
    ?>
    <header>
        <div class="logo">
            <a href="krish.php">
                <img src="logo.png" alt="SkillHub Logo" class="logo-img">
            </a>
        </div>
        <nav>
            <a href="krish.php">Home</a>
        </nav>
    </header>

    <main>
        <!-- Centered Bye Image -->
        <img src="bye.jpg" alt="Goodbye" style="width: 200px; margin-bottom: 20px;">
        <h1>Logout Confirmation</h1>
        <p>You have successfully logged out.</p>
        <a href="login.php" class="btn">Login Again</a>
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
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        window.onload = function () {
            const darkModePreference = localStorage.getItem('darkMode');

            if (darkModePreference === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        };
    </script>
</body>
</html>
