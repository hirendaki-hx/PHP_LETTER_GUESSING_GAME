 <?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: Login.php");
    exit();
}

require_once '../includes/db.php'; 

if(isset($_SESSION['Username'])){
    $username = $_SESSION['Username'];
    $sql = "SELECT score FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $Pscore = $userData['score'];
        $_SESSION['scoreh'] = $Pscore;
    } else {
        $_SESSION['scoreh'] = null;
    }
}

if(isset($_SESSION['Username'])) {
    
    $score_query = "SELECT username, score FROM users ORDER BY score DESC;";
    $score_result = $conn->query($score_query);
    
    
    
    $scores = [];
    while ($row = $score_result->fetch_assoc()) {
        $scores[] = $row;
    }
}
if (isset($_POST['Back'])) {
    
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Missing Letter Game</title>
    <style>
        :root {
            --primary-color: #38AF23;
            --primary-dark: #288019;
            --text-color: #555;
            --light-bg: rgba(255, 255, 255, 0.75);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: url('../images/R-Back-B.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .profile-container {
            background-color: var(--light-bg);
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 800px;
            padding: 30px;
            max-height: 90vh; /* Limit container height */
            overflow: hidden; /* Prevent container scrolling */
            display: flex;
            flex-direction: column;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            flex-shrink: 0; /* Prevent header from shrinking */
        }
        
        .profile-pic-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 36px;
            margin-right: 30px;
        }
        
        .profile-info h2 {
            color: var(--primary-dark);
            margin-bottom: 5px;
        }
        
        .profile-info p {
            color: var(--text-color);
        }
        
        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex: 1; /* Take remaining space */
            overflow: hidden; /* Important for scrolling */
        }
        
        .profile-details {
            display: flex;
            gap: 20px;
            flex-shrink: 0; /* Prevent shrinking */
        }
        
        .detail-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            flex: 1;
            min-width: 200px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .detail-card h3 {
            color: var(--primary-dark);
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .score-history {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex: 1; /* Take remaining space */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Important for scrolling */
        }
        
        .score-history h3 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            flex-shrink: 0;
        }
        
        /* Scrollable table container */
        .table-container {
            flex: 1;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px; /* Fixed height for scrolling */
        }
        
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-container th, 
        .table-container td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table-container th {
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table-container tr:hover {
            background-color: #f5f5f5;
        }
        
        .no-scores {
            text-align: center;
            padding: 20px;
            color: #777;
            flex-shrink: 0;
        }
        
        
        .logout-btn {
            padding: 10px 20px;
            margin-top:20px;
            background-color: #38AF23;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size:17px;
            cursor: pointer;
            transition: all 0.3s ease;
            
        }

        .logout-btn:hover {
            background-color: #288019;
        }
        .no-link {
            text-decoration: none; 
                   
            display: block;        
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
          <div class="profile-pic-large">
                <?php 
                    echo strtoupper(substr($_SESSION['Name'], 0, 1)).strtoupper(substr($_SESSION['Surname'], 0, 1));
                ?>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($_SESSION['Name'] . ' ' . $_SESSION['Surname']); ?></h2>
                <p>@<?php echo htmlspecialchars($_SESSION['Username']); ?></p>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-details">
                <div class="detail-card">
                    <h3>Personal Information</h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['Email']); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($_SESSION['Gender']); ?></p>
                </div>
                
                <div class="detail-card">
                    <h3>Game Statistics</h3>
                    <p><strong>Highest Score:</strong> 
                    <?php
                    if($_SESSION['scoreh'] != null) {
                        echo htmlspecialchars($_SESSION['scoreh']);
                    } else {
                        echo "No scores yet";
                    } 
                    ?>
                    </p>
                </div>
            </div>
            
            <div class="score-history">
                <h3>Leaderboard</h3>
                <?php if (!empty($scores)): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scores as $score): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($score['username']); ?></td>
                                        <td><?php echo htmlspecialchars($score['score']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-scores">
                        <p>No game scores recorded yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <center><form method="post"><button type="submit" name="Back" class="logout-btn">Back to Home</button></form></center>
    </div>
</body>
</html