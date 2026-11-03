<?php
session_start();
require_once 'config.php';

function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$mess = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $uname = trim($_POST['uname']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if (empty($uname)) {
        $errors[] = "Username is required.";
    } elseif (strlen($uname) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters.";
    } elseif ($password !== $password2) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, status, role) VALUES (?, ?, 'pending', 'user')");
        $stmt->bind_param("ss", $uname, $password);
        
        if ($stmt->execute()) {
            $mess[] = "✅ Registered successfully! Please wait for admin approval.";
        } else {
            if (str_contains($conn->error, 'Duplicate entry')) {
                $errors[] = "Username already exists. Try another one.";
            } else {
                $errors[] = "Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventory System</title>
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
        .register-box {
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
        .success-msg {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
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
        .btn-register {
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
        .btn-register:hover {
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
    <div class="register-box">
        <div class="logo-icon">📦</div>
        <h1>Inventory System</h1>
        <h2>Create Your Account</h2>

        <?php foreach ($errors as $e): ?>
            <div class="error-msg"><?= h($e) ?></div>
        <?php endforeach; ?>

        <?php foreach ($mess as $m): ?>
        
