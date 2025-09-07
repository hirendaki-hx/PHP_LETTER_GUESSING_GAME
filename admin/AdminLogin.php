 <?php
session_start();
require_once '../includes/db.php';

if(isset($_POST['user'])){
    header("Location: ../pages/Login.php");
}
$usernameErr = $passwordErr = $Found=  "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Submit'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if (empty($username)) $usernameErr = "*Please enter your username";
        if (empty($password)) $passwordErr = "*Please enter your password";

        if ($usernameErr || $passwordErr) {
            
        } else {
            
            $sql = "SELECT username FROM admins WHERE username='$username' AND password='$password'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $_SESSION['Username'] = $userData['username'];
                header("Location: adminD.php");
                exit(); 
            } else {
                $Found = "*Username and password not Found*";
            }
        }
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../images/R-Back-B.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .FormB {
            background-color: rgba(255, 255, 255, 0.75);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            align-items: center;
        }

        .FormB h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 24px;
            color:rgb(235, 100, 11);
        }

        .form-group {
            margin-bottom: 20px;
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
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            transition: border 0.3s ease;
            background-color: rgba(255, 255, 255, 0.77);
        }

        .form-group input:focus {
            border-color:rgb(235, 100, 11);
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color:rgb(235, 100, 11);
            color: rgb(255, 255, 255);
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.5s ease;
            margin-top: 10px;
            
        }

        input[type="submit"]:hover {
            background-color:rgb(206, 89, 12);
        }
        input[type="submit"].user{
            width: 100%;
            padding: 14px;
            background-color: #38AF23;
            color: rgb(255, 255, 255);
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.5s ease;
            margin-top: 10px;
            
        }

        input[type="submit"].user:hover {
            background-color: #288019;
        }

        .register-link {
            padding-top: 10px;
            text-align: center;
        }

        .register-link a {
            color:rgb(235, 100, 11);;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #2a3e9c;
            text-decoration: underline;
        }
        .error-space {
            min-height: 18px; 
            color: red;
            font-size: 14px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <div class="FormB">
            <h2>Admin Login</h2>

            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username">
                <div class="error-space"><?php echo $usernameErr; ?></div>
                
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                
                <div class="error-space"><?php echo $passwordErr; ?></div>
                
            </div>

            <div class="form-group">
                <div class="form-row">
                    <input type="submit" name="Submit" value="Login">
                    <input type="submit" name="user" value="User" class="user">
                </div>
            </div>
            
            <center><div class="error-space"><?php echo $Found; ?></div></center>
        </div>
    </form>
</body>
</html