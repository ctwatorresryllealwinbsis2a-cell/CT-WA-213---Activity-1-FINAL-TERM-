<?php
session_start();
require_once 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $uname = trim($_POST['uname']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && $password === $user['password']) {
        if ($user['status'] === "approved") {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Your account has not been approved by the admin.";
        }
    } else {
        $errors[] = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-box {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
        }
        .logo-icon {
            text-align: center;
            font-size: 40px;
            margin-bottom: 10px;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }
        h2 {
            text-align: center;
            color: #888;
            font-size: 15px;
            font-weight: normal;
            margin-bottom: 30px;
        }
        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-weight: 500;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .link-text {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }
        .link-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .link-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo-icon">📦</div>
        <h1>Inventory System</h1>
        <h2>Sign In to Your Account</h2>

        <?php foreach ($errors as $e): ?>
            <div class="error-msg"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>

        <form method="post">
            <label for="uname">Username</label>
            <input type="text" id="uname" name="uname" placeholder="Enter your username" required autofocus>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            
            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <p class="link-text">Don't have an account? <a href="register.php">Create Account</a></p>
    </div>
</body>
</html>
