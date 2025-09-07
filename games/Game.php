 <?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: ../pages/Login.php");
    exit();
    
}
if(isset($_POST['logout'])){
    header("Location: ../pages/logout.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
         body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../images/R-Back-B.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            
        }
        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-right {
            display: flex;
            align-items: center;
        }
        .top-left-options {
            position: absolute;
            top: 150px; /* Below navbar */
            left: 30px;
            display: flex;
            gap: 20px;
        }

        .profile-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #38AF23;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .profile-circle:hover {
            background-color: #288019;
        
        }

        .nav-menu {
            display: flex;
            gap: 25px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #555;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover {
            color: #38AF23;
            background-color: rgba(56, 175, 35, 0.1);
        }

        .logout-btn {
            padding: 8px 20px;
            background-color: #38AF23;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #288019;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px);
            text-align: center;
        }

        .welcome-message {
            text-align: left;
            padding-top: 20px;
            padding-left:30px;
            font-weight:bold;
            font-size: 24px;
            color: white;
        }

        .options-container {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }

        .option-card {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            cursor: pointer;
        }


        .option-card:hover {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .option-card h3 {
            color: #38AF23;
            margin: 0 0 8px 0;
            font-size: 16px;
        }

        .option-card p {
            color: #666;
            margin: 0;
            font-size: 13px;
        }
        .no-link {
            text-decoration: none; 
                   
            display: block;        
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="nav-left">
           <a href="../pages/profile.php"  class="no-link">
                <div class="profile-circle" name="profile" >
            
                    <?php 
                    if (isset($_SESSION['Name']) && isset($_SESSION['Surname'])) {
                        echo strtoupper(substr($_SESSION['Name'], 0, 1)).strtoupper(substr($_SESSION['Surname'], 0, 1)); 
                    } else {
                        echo "U";
                    }
                    ?>
                </div>
            </a> 
            <div class="nav-menu">
                <a href="../index.php">Home</a>
                <a href="Game.php">Games</a>
                <a href="#">About</a>
                <a href="#">Help</a>
            </div>
        </div>
        <div class="nav-right">
           <form method="post"><button class="logout-btn"  name="logout">Logout</button></form>
        </div>
    </div>

    <!-- Main Content -->
    <!-- Top-left options -->
    <div class="top-left-options">
        <a href="G_Letter.php" class="no-link">
            <div class="option-card">
                <h3>Letter Gussing</h3>
                <p>Guess the missing letter</p>
            </div>
        </a>
        
    </div>

    <!-- Welcome message -->
    <div class="welcome-message">
        Welcome back, <?php echo htmlspecialchars($_SESSION['Username']); ?>!
    </div>
</body>
</html