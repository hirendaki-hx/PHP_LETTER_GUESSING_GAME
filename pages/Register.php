 <?php
require_once '../includes/db.php';

$genderErr = $emailErr = $surnameErr = $nameErr = $usernameErr = $passwordErr= $confirm_passwordErr ="";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Submit'])) {
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $email = trim($_POST['email']);
        $gender = $_POST['gender'];
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

       
        if (empty($name)) $nameErr = "*Please enter your name";
        if (empty($surname)) $surnameErr = "*Please enter your surname";
        if (empty($email)) $emailErr = "*Please enter your email";
        if (empty($gender)) $genderErr = "*Please select your gender";
        if (empty($username)) $usernameErr = "*Please enter your username";
        if (empty($password)) $passwordErr = "*Please enter your password";
        if (empty($confirm_password)) $confirm_passwordErr = "*Please confirm your password";

        
        if ($nameErr || $surnameErr || $emailErr || $genderErr || $usernameErr || $passwordErr || $confirm_passwordErr) {
            
        } else {
            
            $check = "SELECT username, email FROM users WHERE username='$username' OR email='$email'";
            $check_r = $conn->query($check);
            $userData = $check_r->fetch_assoc();
            if ($userData['username'] == $username || $userData['email'] == $email) {
                $usernameErr = "Username or Email already exists!";
            } elseif ($password !== $confirm_password) {
                $confirm_passwordErr = "Password and Confirm Password do not match!";
            } else {
                $sql = "INSERT INTO users (name, surname, email, gender, username, password)
                        VALUES ('$name', '$surname', '$email', '$gender', '$username', '$password')";
                if ($conn->query($sql)) {
                    echo "<script>alert('Registration successful!'); </script>";
                    header("Location: Login.php");
                    exit();
                } else {
                    echo "<script>alert('Registration not successful!');</script>";
                }
            }
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    
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
            padding: 20px;
            padding-left: 40px;
            padding-right: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
        }

        .FormB h2 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 24px;
            color: #288019;
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

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            transition: border 0.3s ease;
            background-color: rgba(255, 255, 255, 0.75); 
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #38AF23;
        }

        
        .radio-group {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 10px;
        }

        .radio-group input {
            margin-right: 6px;
            
        }
        .radio-group input[type="radio"] {
            accent-color: #38AF23;
        }
        

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #38AF23;
            color: rgb(255, 255, 255);
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.5s ease; 
        }

        input[type="submit"]:hover {
            background-color: #288019;
             
        }
        .login-link{
            padding-top: 10px;
            text-align: center;
        }

        .login-link a {
            color: #38AF23;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #2a3e9c;
            text-decoration: underline;
        }
        .error-space {
            min-height: 18px; 
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <div class="FormB">
            
            <h2>Registration Form</h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="name">First Name</label>
                    <input type="text" id="name" name="name">
                    <div class="error-space"><?php echo $nameErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="surname">Last Name</label>
                    <input type="text" id="surname" name="surname">
                    <div class="error-space"><?php echo $surnameErr; ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="Male"> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                        <label><input type="radio" name="gender" value="Other"> Other</label>
                        <div class="error-space"><?php echo $genderErr; ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">
                    <div class="error-space"><?php echo $usernameErr; ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                    <div class="error-space"><?php echo $emailErr; ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <div class="error-space"><?php echo $passwordErr; ?></div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                    <div class="error-space"><?php echo $confirm_passwordErr; ?></div>
                </div>
            </div>

            <input type="submit" name="Submit" value="Register">
            <div class="login-link">
                Already have an account? <a href="Login.php">Login</a>
            </div>
        </div>
    </form>
</body>
</html>
