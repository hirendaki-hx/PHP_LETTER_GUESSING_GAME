 <?php
session_start();
if(!isset($_SESSION['Username']))
{
    header("Location:../admin/AdminLogin.php");
    exit();
}

require_once '../includes/db.php';

// Initialize variables
$usernameErr = $confirm_passwordErr = $check_Q = "";
$name = $surname = $gender = $username = $email = $password = $confirm_password = $Id = "";

// Handle logout
if(isset($_POST['logout'])) {
    session_destroy();
    header("Location:../admin/AdminLogin.php");
    exit();
}

// Handle navigation between different views
if(isset($_POST['insert1'])) {
    $_SESSION['view'] = 'insert';
} elseif(isset($_POST['update'])) {
    $_SESSION['view'] = 'update';
} elseif(isset($_POST['delete'])) {
    $_SESSION['view'] = 'delete';
} elseif(isset($_POST['leaderboard'])) {
    $_SESSION['view'] = 'leaderboard';
} elseif(isset($_POST['dashboard']) || isset($_POST['cancel_operation'])) {
    // This is the fix: Clear the view to return to dashboard
    unset($_SESSION['view']);
}

// Handle insert operation
if(isset($_POST['submit_insert'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if(empty($username)) {
        $usernameErr = "Username is required!";
    }
    
    if($password !== $confirm_password) {
        $confirm_passwordErr = "Password and Confirm Password do not match!";
    }
    
    if(empty($usernameErr) && empty($confirm_passwordErr)) {
        // Check if username or email already exists
        $check = "SELECT username, email FROM users WHERE username='$username' OR email='$email'";
        $check_r = $conn->query($check);
        
        if($check_r && $check_r->num_rows > 0) {
            $usernameErr = "Username or Email already exists!";
        } else {
            // Insert new user
            $sql = "INSERT INTO users (name, surname, email, gender, username, password)
                    VALUES ('$name', '$surname', '$email', '$gender', '$username', '$password')";
            if ($conn->query($sql)) {
                $check_Q = "One Record Added successfully!";
                // Clear form fields and return to dashboard
                $name = $surname = $gender = $username = $email = $password = $confirm_password = "";
                unset($_SESSION['view']);
            } else {
                $check_Q = "Error: " . $conn->error;
            }
        }
    }
}

// Handle update operation
if(isset($_POST['update_insert'])) {
    $Id = $_POST['id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if(empty($username)) {
        $usernameErr = "Username is required!";
    }
    
    if($password !== $confirm_password) {
        $confirm_passwordErr = "Password and Confirm Password do not match!";
    }
    
    if(empty($usernameErr) && empty($confirm_passwordErr)) {
        // Update user
        $sql = "UPDATE users SET name='$name', surname='$surname', email='$email', 
                gender='$gender', username='$username', password='$password' WHERE id='$Id'";
        
        if ($conn->query($sql)) {
            if($conn->affected_rows > 0) {
                $check_Q = "Record updated successfully!";
                // Clear form fields and return to dashboard
                $Id = $name = $surname = $gender = $username = $email = $password = $confirm_password = "";
                unset($_SESSION['view']);
            } else {
                $check_Q = "No record found with that ID!";
            }
        } else {
            $check_Q = "Error: " . $conn->error;
        }
    }
}

// Handle delete operation
if(isset($_POST['submit_delete'])) {
    $Id = $_POST['Id'];
    $username_to_delete = $_POST['username'];
    
    if(!empty($Id) && !empty($username_to_delete)) {
        // Verify the user exists with the provided ID and username
        $verify_sql = "SELECT id FROM users WHERE id='$Id' or username='$username_to_delete'";
        $verify_result = $conn->query($verify_sql);
        
        if($verify_result && $verify_result->num_rows > 0) {
            // Delete the user
            $sql = "DELETE FROM users WHERE id='$Id' or username='$username_to_delete'";
            if($conn->query($sql)) {
                $check_Q = "User deleted successfully!";
                $Id = $username_to_delete = "";
                unset($_SESSION['view']);
            } else {
                $check_Q = "Error deleting user: " . $conn->error;
            }
        } else {
            $check_Q = "No user found with that ID and username combination!";
        }
    } else {
        $check_Q = "Please provide both ID and username!";
    }
}

// Fetch all users for the dashboard
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$users = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fetch scores for leaderboard
$score_query = "SELECT username, score FROM users ORDER BY score DESC";
$score_result = $conn->query($score_query);
$scores = [];
if ($score_result && $score_result->num_rows > 0) {
    while ($row = $score_result->fetch_assoc()) {
        $scores[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profiles - Missing Letter Game</title>
    <style>
        :root {
            --primary-color: rgb(235, 100, 11);
            --primary-dark: rgb(206, 89, 12);
            --text-color: #555;
            --light-bg: rgba(255, 255, 255, 0.85);
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
            background-attachment: fixed;
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Fixed navbar at the top */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 15px 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-info {
            font-weight: bold;
            color: var(--primary-dark);
            font-size: 18px;
        }
        
        .logout-btn {
            padding: 7px 25px;
            margin-top: 5px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: var(--primary-dark);
        }
        
        /* Main content container */
        .main-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 20px;
            flex: 1;
        }
        
        .profile-container {
            background-color: var(--light-bg);
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 1100px;
            
            padding: 30px;
            display: flex;
            flex-direction: column;
            max-height: 75vh;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            flex-shrink: 0;
        }
        
        .profile-pic-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 40px;
            margin-right: 20px;
        }
        
        .profile-info h2 {
            color: var(--primary-dark);
            margin-bottom: 5px;
            font-size: 24px;
        }
        
        .profile-info p {
            color: var(--text-color);
            font-size: 16px;
        }
        
        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex: 1;
            overflow: hidden;
        }
        
        .score-history {
            background-color: white;
            border-radius: 8px;
            
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .score-history h3 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            flex-shrink: 0;
            font-size: 20px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .table-container {
            flex: 1;
            overflow: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 400px;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .users-table th, 
        .users-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .users-table th {
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            font-weight: 600;
        }
        
        .users-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .users-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .users-table td {
            color: var(--text-color);
        }
        
        .no-users, .no-scores {
            text-align: center;
            padding: 20px;
            color: #777;
            flex-shrink: 0;
        }
        
        .back-btn {
            padding: 9px 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            margin-top: 15px;
            margin-right: 15px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .insert-container, .delete-container {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            max-width: 1100px;
            width: 90%;
        }
        
        .insert-header {
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 25px;
            font-size: 22px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-size: 14px;
            margin-bottom: 6px;
            color: #555;
            font-weight: bold;
        }
        
        .form-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            transition: border 0.3s ease;
            background-color: rgba(255, 255, 255, 0.75); 
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(235, 100, 11, 0.3);
        }
        
        .radio-group {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 10px;
        }
        
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
            cursor: pointer;
        }
        
        .form-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        .submit-btn {
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .cancel-btn {
            padding: 12px 25px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cancel-btn:hover {
            background-color: #bd2130;
        }
        
        .error-space {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            min-height: 20px;
        }
        
        .success-msg {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .back-btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .leaderboard-container {
            background-color: var(--light-bg);
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 1100px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            margin: 20px auto;
            max-height: 75vh; 
        }

        .leaderboard-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex: 1;
            overflow: hidden;
        }

        
        .score-history {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            max-height: 400px; 
        }

        .table-container {
            flex: 1;
            overflow: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px; 
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 400px; 
        }

      
        .users-table th {
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            font-weight: 600;
        }

        
        @media (max-width: 992px) {
            .navbar {
                padding: 12px 20px;
            }
            
            .profile-container {
                max-width: 95%;
                padding: 20px;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .users-table {
                min-width: 600px;
            }
            
            .profile-pic-large {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }
        }
        
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-pic-large {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .nav-left, .nav-right {
                width: 100%;
                justify-content: center;
            }
            
            .form-row {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar at the top -->
    <div class="navbar">
        <div class="nav-left">
            <div class="admin-info">Admin Dashboard</div>       
        </div>
        <div class="nav-right">
            <div class="admin-info">Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?></div>
            <form method="post">
                <button class="logout-btn" name="logout">Logout</button>
            </form>
        </div>
    </div>
    
    <center>
        <?php if (!empty($check_Q)): ?>
            <div class="success-msg"><?php echo $check_Q; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['view']) && $_SESSION['view'] == 'insert'): ?>
            <!-- Insert Form -->
            <div class="insert-container">
                <h3 class="insert-header">Add New User</h3>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>" >
                        </div>
                        <div class="form-group">
                            <label for="surname">Last Name</label>
                            <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>> Male</label>
                                <label><input type="radio" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>> Female</label>
                                <label><input type="radio" name="gender" value="Other" <?php echo ($gender == 'Other') ? 'checked' : ''; ?>> Other</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo $username; ?>" >
                            <div class="error-space"><?php echo $usernameErr; ?></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" value="<?php echo $password; ?>" >
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>" >
                            <div class="error-space"><?php echo $confirm_passwordErr; ?></div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit_insert" class="submit-btn">Add User</button>
                        <button type="submit" name="cancel_operation" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </div>
            
        <?php elseif (isset($_SESSION['view']) && $_SESSION['view'] == 'update'): ?>
            <!-- Update Form -->
            <div class="insert-container">
                <h3 class="insert-header">Update User</h3>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id">User ID</label>
                            <input type="text" id="id" name="id" value="<?php echo $Id; ?>" >
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="surname">Last Name</label>
                            <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>> Male</label>
                                <label><input type="radio" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>> Female</label>
                                <label><input type="radio" name="gender" value="Other" <?php echo ($gender == 'Other') ? 'checked' : ''; ?>> Other</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                            <div class="error-space"><?php echo $usernameErr; ?></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" value="<?php echo $password; ?>">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                            <div class="error-space"><?php echo $confirm_passwordErr; ?></div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_insert" class="submit-btn">Update User</button>
                        <button type="submit" name="cancel_operation" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </div>
            
        <?php elseif (isset($_SESSION['view']) && $_SESSION['view'] == 'delete'): ?>
            <!-- Delete Form -->
            <div class="delete-container">
                <h3 class="insert-header">Delete User</h3>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Id">User ID</label>
                            <input type="text" id="Id" name="Id" value="<?php echo $Id; ?>" >
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo $username; ?>" >
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit_delete" class="cancel-btn">Delete User</button>
                        <button type="submit" name="cancel_operation" class="back-btn">Cancel</button>
                    </div>
                </form>
            </div>
            
        
            <?php elseif (isset($_SESSION['view']) && $_SESSION['view'] == 'leaderboard'): ?>    
                <!-- Leaderboard View -->
                <div class="main-container">
                    <div class="leaderboard-container">
                        <div class="profile-header">
                            <div class="profile-pic-large">
                                <?php echo strtoupper(substr($_SESSION['Username'], 0, 1)); ?>
                            </div>
                            <div class="profile-info">
                                <h2>Leaderboard</h2>
                                <p>Top scores from all users</p>
                            </div>
                        </div>
                        
                        <div class="leaderboard-content">
                            <div class="score-history">
                                <h3>Top Scores</h3>
                                <?php if (!empty($scores)): ?>
                                    <div class="table-container">
                                        <table class="users-table">
                                            <thead>
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Username</th>
                                                    <th>Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $rank = 1; ?>
                                                <?php foreach ($scores as $score): ?>
                                                    <tr>
                                                        <td><?php echo $rank++; ?></td>
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
                        
                        <div class="back-btn-container">
                            <form method="post">
                                <button type="submit" name="dashboard" class="back-btn">Back to Dashboard</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
            <!-- Default Dashboard View -->
            <div class="main-container">
                <div class="profile-container">
                    <div class="profile-header">
                        <div class="profile-pic-large">
                            <?php echo strtoupper(substr($_SESSION['Username'], 0, 1)); ?>
                        </div>
                        <div class="profile-info">
                            <h2>Admin Dashboard</h2>
                            <p>@<?php echo htmlspecialchars($_SESSION['Username']);?></p>
                        </div>
                    </div>
                
                    <div class="profile-content">
                        <div class="score-history">
                            <h3>All Users</h3>
                            <?php if (!empty($users)): ?>
                                <div class="table-container">
                                    <table class="users-table">
                                        <thead>
                                            <tr>
                                                <?php 
                                                $firstUser = $users[0];
                                                foreach ($firstUser as $column => $value): ?>
                                                    <th><?php echo htmlspecialchars(ucfirst($column)); ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <?php foreach ($user as $value): ?>
                                                        <td><?php echo htmlspecialchars($value); ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="no-users">
                                    <p>No users found in the database.</p>
                                    <p>Please check your database connection and table structure.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="back-btn-container">
                        <form method="post">
                            <button type="submit" name="insert1" class="back-btn">Insert User</button>
                            <button type="submit" name="update" class="back-btn">Update User</button>
                            <button type="submit" name="delete" class="back-btn">Delete User</button>
                            <button type="submit" name="leaderboard" class="back-btn">Leaderboard</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </center>
</body>
</html