
# SkillHub

SkillHub is a web-based platform designed to foster collaboration, skill-sharing, and community building among students and teachers at Jaypee Institute of Information Technology (JIIT). The platform enables users to share skills, join collaborative projects, enroll in courses, and connect with peers in a meaningful way.

---

## Features

### Core Functionalities
- **User Registration and Login**: Secure user authentication with profile creation.
- **Skill Sharing**: Users can list their skills, browse others' skills, and connect for learning or collaboration.
- **Collaborative Projects**: Create, join, and manage projects; recruit team members with required skills.
- **Courses and Learning**: Browse, enroll in, or create courses to enhance skills and knowledge.
- **Real-Time Messaging**: Chat with other users for instant communication and project discussions.
- **Posts and Interactions**: Share updates, like posts, and engage with the community.
- **Dark Mode Support**: Switch between light and dark themes for better accessibility.

---

## Tools and Technologies

### Backend
- **PHP**: Handles server-side logic, session management, and database interactions.
- **MySQL**: Database for storing user data, skills, courses, posts, and messages.

### Frontend
- **HTML5**: Structure and layout of the website.
- **CSS3**: Styling with light and dark modes.
- **JavaScript**: Adds interactivity and responsiveness to the platform.

### Real-Time Functionality
- **AJAX**: Provides a seamless user experience by enabling actions like liking posts or sending messages without page reloads.

### Other Tools
- **Git**: Version control for project management and collaboration.
- **XAMPP**: Local server environment for testing and development.

---

## How to Run Locally

1. **Install XAMPP**  
   Download and install [XAMPP](https://www.apachefriends.org/index.html) to set up a local PHP and MySQL environment.

2. **Clone the Repository**  
   Clone this repository to your local machine:
   ```bash
   git clone <repository-URL>
   cd <repository-folder>
   ```

3. **Set Up the Database**  
   - Open **phpMyAdmin** by visiting `http://localhost/phpmyadmin`.
   - Create a new database (e.g., `skillhub`).
   - Import the provided SQL schema (`skillhub.sql`) into the database.

4. **Configure the Database Connection**  
   - Open the `db.php` file in the project folder.
   - Update the database credentials to match your local setup:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "skillhub";
     ```

5. **Run the Project**  
   - Move the project folder to the `htdocs` directory in your XAMPP installation.
   - Start the Apache and MySQL services in the XAMPP control panel.
   - Open your browser and visit `http://localhost/<project-folder-name>`.

---

## Folder Structure

- **`/assets`**: Contains static files like images, CSS, and JavaScript.
- Rest all the files
---

## Features Overview

| Feature                   | Description                                                                 |
|---------------------------|-----------------------------------------------------------------------------|
| **Registration/Login**    | Allows users to create accounts and securely log in.                       |
| **Skill Listing**         | Users can add their skills and browse others' profiles to find collaborators. |
| **Project Collaboration** | Create and recruit for projects based on specific skill requirements.      |
| **Messaging**             | Real-time chat functionality to enhance collaboration.                     |
| **Dark/Light Modes**      | Toggle between themes for an optimal viewing experience.                   |
 

---

## License

This project is licensed for academic purposes and should not be used for commercial applications.

---
```
