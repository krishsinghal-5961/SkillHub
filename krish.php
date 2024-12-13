<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Home</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure footer is at the bottom */
            justify-content: space-between;
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

        /* Dark Mode Toggle */
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

        /* Hero Section */
        .hero {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            padding: 80px 20px;
            text-align: center;
            background-size: cover;
            background-position: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
        }

        .cta-button {
            background-color: white;
            color: #007bff;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.2rem;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #0056b3;
            color: white;
        }

  /* Slider Section */
  .slider {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
            background-color: #fff;
            overflow: hidden;
            height: 300px;
        }

        .slider-images {
            display: flex;
            gap: 0px;
            transition: transform 0.3s ease;
        }

        .slider img {
            width: 290px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.4s linear, box-shadow 0.4s linear;
        }

        /* Enlarging effect on hover */
        .slider img:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }


        /* Why Choose Us Section */
        .why-choose-us {
            padding: 60px 20px;
            background-color: #e3e8f0;
            text-align: center;
        }

        .why-choose-us h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #333;
        }

        .why-choose-us ul {
            list-style-type: none;
            padding: 0;
        }

        .why-choose-us ul li {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #555;
        }

        /* Partners Section */
        .partners {
            padding: 40px 20px;
            text-align: center;
        }

        .partners h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
        }

        .partners img {
            max-width: 150px;
            margin: 0 20px;
            filter: grayscale(100%);
            transition: filter 0.3s ease, transform 0.3s ease;
        }

        .partners img:hover {
            filter: none;
            transform: scale(1.05);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            width: 100%;
        }

/* General Dark Mode Styles */
body.dark-mode {
    background-color: #1b1b1b; /* Dark background */
    color: #e0e0e0; /* Light text color */
    transition: background-color 0.5s ease, color 0.5s ease;
}

/* Header */
body.dark-mode header {
    background: linear-gradient(145deg, #2a2a2a, #1b1b1b);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
}

body.dark-mode header a {
    color: #e0e0e0;
    transition: color 0.3s ease;
}

body.dark-mode header a:hover {
    color: #4db8ff; /* Link hover color */
}

/* Dark Mode Toggle Button */
body.dark-mode .dark-mode-toggle {
    background-color: #4db8ff; /* Toggle button background */
    color: #1b1b1b; /* Toggle button text color */
    border: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode .dark-mode-toggle:hover {
    background-color: #007bff; /* Hover background color */
    color: #ffffff; /* Hover text color */
}

/* Hero Section */
body.dark-mode .hero {
    background: linear-gradient(to right, #3c3c3c, #2b2b2b);
    color: #f0f0f0;
    padding: 80px 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.7);
    transition: background 0.5s ease;
}

body.dark-mode .hero h1 {
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
}

body.dark-mode .hero p {
    font-size: 1.4rem;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.6);
}

/* Call to Action Button */
body.dark-mode .cta-button {
    background-color: #4db8ff; /* CTA button background */
    color: #1b1b1b; /* CTA button text color */
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode .cta-button:hover {
    background-color: #007bff; /* CTA button hover background */
    color: #ffffff; /* CTA button hover text color */
}

/* Slider Section */
body.dark-mode .slider {
    background-color: #2b2b2b; /* Slider background */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
}

body.dark-mode .slider img {
    filter: brightness(0.8); /* Image brightness */
    border: 2px solid #4db8ff; /* Image border color */
    border-radius: 10px; /* Image border radius */
    transition: transform 0.3s ease, filter 0.3s ease;
}

body.dark-mode .slider img:hover {
    transform: scale(1.1); /* Scale on hover */
    filter: brightness(1); /* Brightness on hover */
}

/* Why Choose Us Section */
body.dark-mode .why-choose-us {
    background-color: #333a40; /* Section background */
    color: #e0e0e0; /* Section text color */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
    padding: 60px 20px;
}

body.dark-mode .why-choose-us h2 {
    color: #4db8ff; /* Section heading color */
}

body.dark-mode .why-choose-us ul li {
    color: #c0c0c0; /* List item color */
}

/* Partners Section */
body.dark-mode .partners {
    background-color: #2a2a2a; /* Section background */
    color: #ffffff; /* Section text color */
    padding: 40px 20px;
    transition: background-color 0.5s ease, color 0.5s ease;
}

body.dark-mode .partners h2 {
    color: #4db8ff; /* Section heading color */
}

body.dark-mode .partners img {
    filter: grayscale(100%); /* Image grayscale */
    transition: filter 0.3s ease, transform 0.3s ease;
}

body.dark-mode .partners img:hover {
    filter: grayscale(0%); /* Remove grayscale on hover */
    transform: scale(1.1); /* Scale on hover */
}

/* Footer */
body.dark-mode footer {
    background: linear-gradient(145deg, #2a2a2a, #1b1b1b);
    color: #f0f0f0;
    padding: 20px;
    box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.7);
}

body.dark-mode footer p {
    font-size: 1.1rem;
    color: #ffffff;
    opacity: 0.8; /* Opacity effect */
    transition: opacity 0.3s ease;
}

body.dark-mode footer p:hover {
    opacity: 1; /* Increase opacity on hover */
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
        <a href="about.php">About</a>
        <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <h1>Welcome to SkillHub</h1>
    <p>Your one-stop platform to enhance skills, find projects, and connect with potential recruiters. Join now to unlock opportunities and boost your career!</p>
    <a href="register.php" class="cta-button">Get Started</a>
</section>

<!-- Slider Section -->
<div class="slider" id="skillSlider">
    <div class="slider-images" id="sliderImages">
        <?php
            $images = ["slider_image1.jpg", "slider_image2.jpg", "slider_image3.jpg", "slider_image4.jpg", "slider_image5.jpg", "slider_image6.jpg", "slider_image7.jpg","slider_image8.jpg" ];
            foreach ($images as $image) {
                echo "<img src='$image' alt='Skill Image' onclick=\"window.location.href='register.php'\">";
            }
        ?>
    </div>
</div>

<!-- Why Choose Us Section -->
<section class="why-choose-us">
    <h2>Why Choose SkillHub?</h2>
    <ul>
        <?php
            $benefits = ["Expert-Led Courses", "Flexible Learning at Your Own Pace", "Access to an Active Community of Learners"];
            foreach ($benefits as $benefit) {
                echo "<li>$benefit</li>";
            }
        ?>
    </ul>
</section>

<!-- Partners Section -->
<section class="partners">
    <h2>Our Partners</h2>
    <a href="https://www.jiit.ac.in">
        <img src="jiit_logo.jpg" alt="JIIT Logo">
    </a>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SkillHub | Made with ❤️ by the SkillHub Team</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
</footer>

<script>
    let currentSlide = 0;
    let slides = document.querySelectorAll('.slider img');
    let totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.display = i === index ? 'block' : 'none';
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    setInterval(nextSlide, 2000); // Automatically change slide every 5 seconds

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
