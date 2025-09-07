 <?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: ../Login.php");
    exit();
    
}
if(isset($_POST['logout'])){
    header("Location: ../pages/logout.php");
    exit();
}

if (isset($_POST['restart_game'])) {
    unset($_SESSION['player_name']);
    unset($_SESSION['score']);
    unset($_SESSION['round']);
    unset($_SESSION['total_rounds']);
    unset($_SESSION['current_word']);
    unset($_SESSION['missing_letter']);
    unset($_SESSION['masked_word']);
    unset($_SESSION['message']);
    unset($_SESSION['message_class']);
    unset($_SESSION['game_over']);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['Back'])) {
    
    header("Location: ../index.php");
    exit();
}

$Wusername =""; 
if(isset($_POST['player_name'])){
    $nickname = $_POST['player_name'];
    $_SESSION['player_name'] = $nickname;
    $_SESSION['score'] = 0;
    $_SESSION['round'] = 1;
    $_SESSION['total_rounds'] = 5;
    generateNewWord(); 
    header("Location: ".$_SERVER['PHP_SELF']); 
    exit();
}

function generateNewWord() {
    $words = ["cat", "sun", "puzzle", "guitar", "labyrinth", "cryptography", "dog", "book", "tree", "blue", "journey", "harbor", "rhythm", "onomatopoeia","transcendence","photosynthesis" ];

    $word = $words[array_rand($words)]; 
    $pos = rand(0, strlen($word) - 1);  
    
    $_SESSION['current_word'] = $word;
    $_SESSION['missing_letter'] = $word[$pos];
    $_SESSION['masked_word'] = substr($word, 0, $pos) . "_" . substr($word, $pos + 1);
}

if (isset($_POST['guess_letter'])) {
    $guess = strtolower($_POST['guess']);
    
    if ($guess === $_SESSION['missing_letter']) {
        $_SESSION['message'] = "Correct! Answer";
        $_SESSION['message_class'] = "success";
        $_SESSION['score'] += 10;
    } else {
        $_SESSION['message'] = "Incorrect! Answer";
        $_SESSION['message_class'] = "error";
    }
    
    $_SESSION['round']++;
    
    if ($_SESSION['round'] > $_SESSION['total_rounds']) {
        
        require_once '../includes/db.php'; 

        if ($conn->connect_error) {
            echo "<script>alert('Connection is Failed!');</script>";
            exit();
        }
        
        $username = $_SESSION['Username'];
        $sql = "SELECT score FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        $userData = $result->fetch_assoc();
        $Pscore = $userData['score'];
        
        $Pscore += $_SESSION['score'];
        
        $sql = "UPDATE users SET score = '$Pscore' WHERE username='$username'";
        $result = $conn->query($sql);

        $_SESSION['game_over'] = true;
    } else {
        generateNewWord();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing Letter Game</title>
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

       
        
        /* Game Container */
        .game-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: white;
            text-align: center;
        }
        
        .game-content {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            color: #333;
            max-width: 500px;
            max-height:450px;
            width: 100%;
            margin: 20px;
        }
        
        .start-screen, .end-screen {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            color: #333;
            max-width: 500px;
            width: 100%;
        }
        
        .start-screen h1, .end-screen h2 {
            color: #38AF23;
            margin-top: 0;
        }
        
        input[type="text"] {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            margin: 10px 0;
            width: 250px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            border-color: #38AF23;
            outline: none;
        }
        
        button {
            padding: 8px 20px;
            background-color: #38AF23;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        button:hover {
            background-color: #288019;
        }
        
        .player-info {
            display: flex;
            justify-content: space-around;
            width: 94%;
            margin: 20px 0;
            
            padding: 15px;
            border-radius: 8px;
            color: #333;
            font-weight: bold;
            
            
        }
        
        .word-display {
            font-size: 36px;
            letter-spacing: 10px;
            margin: 30px 0;
            
            padding: 15px;
            border-radius: 10px;
            color: #38AF23;
            font-weight: bold;
            
        }
        
        .message {
            padding: 7px 10px;
            margin: 15px 0;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        
        .success {
            background-color: rgba(41, 207, 0, 0.4);
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: rgba(207, 0, 0, 0.4);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .action-buttons {
            margin-top: 25px;
        }
        
        .final-score {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            color: #38AF23;
        }
        
        h1 {
            color: white;
            margin-bottom: 20px;
        }
        
        
        
        label {
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #333;
            
        }
        .error-space {
            min-height: 18px; 
            color: red;
            font-size: 14px;
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
            
           
        </div>
        <div class="nav-right">

            <form method="post"><button class="logout-btn"  name="logout" style= "margin-right:15px;" >Logout</button></form>
            <form method="post"><button type="submit" name="Back" class="logout-btn">Back to Home</button></form>
            
        </div>
    </div>

    <div class="game-container">
        <?php if (!isset($_SESSION['player_name'])): ?>
            <!-- Start Screen -->
            <div class="start-screen">
                <h1>Missing Letter Game</h1>
                <p>Guess the missing letter in each word to score points!</p>
                <form method="post">
                    <input type="text" name="player_name" placeholder="Nick Name" required>
                    <button type="submit" name="start_game" style= "padding: 12px 25px; padding-top:14px; padding-bottom:13px; font-size: 16px;">Start Game</button>
                    
                </form>
            </div>
            
        <?php elseif (isset($_SESSION['game_over'])): ?>
            <!-- End Screen -->
            <div class="end-screen">
                <h2>Game Over!</h2>
                <p>Great job, <?php echo htmlspecialchars($_SESSION['player_name']); ?>!</p>
                <div class="final-score">Final Score: <?php echo $_SESSION['score']; ?></div>
                <p>Your score has been recorded.</p>
                <div class="action-buttons">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="restart_game" class="restart" style= "padding: 12px 25px; padding-top:14px; padding-bottom:13px; font-size: 16px;">Play Again</button>
                    </form>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Game Screen -->
            
            <div class="game-content">
                <h1 style="color: #38AF23;">Missing Letter</h1>
                <div class="player-info">
                    <div>Player: <?php echo htmlspecialchars($_SESSION['player_name']); ?></div>
                    <div>Score: <?php echo $_SESSION['score']; ?></div>
                    <div>Round: <?php echo $_SESSION['round']; ?>/<?php echo $_SESSION['total_rounds']; ?></div>
                </div>
                
                <div class="word-display">
                    <?php echo isset($_SESSION['masked_word']) ? strtoupper($_SESSION['masked_word']) : 'Word loading...'; ?>
                </div>
                
                <form method="post">
                    <label for="guess">Guess the missing letter:</label>
                    <input type="text" name="guess" id="guess" maxlength="1" required autofocus>
                    <button type="submit" name="guess_letter" style= "padding: 12px 25px; padding-top:14px; padding-bottom:13px; font-size: 16px;">Submit</button>
                </form>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="message <?php echo $_SESSION['message_class']; ?>">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_class']); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html