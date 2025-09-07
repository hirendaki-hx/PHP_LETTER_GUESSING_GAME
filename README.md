# 🎯 Missing Letter Game

A web-based word game where players test their vocabulary by guessing missing letters in words to earn points and climb the leaderboard.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)

## ✨ Features

- 👤 **User Authentication** - Register and login system
- 🎮 **Interactive Gameplay** - Guess missing letters in words
- 📊 **Score System** - Earn points for correct answers
- 🏆 **Leaderboard** - Compete with other players
- 👨‍💼 **Admin Panel** - Manage users and view statistics
- 📱 **Responsive Design** - Works on desktop and mobile

## 📁 Project Structure
```
missing-letter-game/
├── admin/                 # Admin functionality
│   ├── adminD.php        # Admin dashboard
│   └── AdminLogin.php    # Admin login page
├── assets/               # Static files
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image assets
├── database/             # Database files
│   └── schema.sql        # Database schema
├── includes/             # Reusable components
│   ├── db.php           # Database connection
│   └── config.php       # Configuration (create from config-sample.php)
├── games/                # Game functionality
│   ├── G_Letter.php     # Letter guessing game
│   └── Game.php         # Games menu page
├── pages/                # Main application pages
│   ├── index.php        # Home page
│   ├── Login.php        # User login
│   ├── logout.php       # Logout handler
│   ├── profile.php      # User profile
│   └── Register.php     # User registration
├── .gitignore           # Git ignore rules
└── README.md            # This file
```

## 🚀 Quick Start

### Prerequisites
- Web server (XAMPP, WAMP, LAMP, or MAMP)
- PHP 7.0 or higher
- MySQL 5.6 or higher

### Installation

### 1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/PHP-projects.git
   cd PHP-projects/missing-letter-game
   ```
### 2. Set up the database
- Create a MySQL database named 'missing_letter_game'
- Import the SQL schema from 'database/schema.sql'

### 3. Configure the application
- Copy 'includes/config-sample.php' to 'includes/config.php'
- Update database credentials in 'includes/config.php'

### 4. Access the application
- Open your web browser
- Navigate to 'http://localhost/missing-letter-game/pages/'

## 🛠️ Adding New Words
- Edit the '$words array' in 'games/G_Letter.php':
   ```
   $words = ["cat", "sun", "puzzle", "guitar", "labyrinth", "cryptography"];
   ```
## 📝 License
- This project is licensed under the MIT License - see the 'LICENSE' file for details.

   

