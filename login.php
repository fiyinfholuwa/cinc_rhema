<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 420px;
        }

        .box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 600;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #aaa;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .msg {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .box {
                padding: 30px 25px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="box">
        <h2>Welcome Back</h2>
        
        <p class="subtitle">Please login to your account         <a href="index.php">go to home</a>
</p>

        <div id="msg"></div>

        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" placeholder="Enter your username" required>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" placeholder="Enter your password" required>
        </div>

        <button onclick="loginUser()">Login</button>

        <div class="footer-links">
            <a href="#">Forgot password?</a>
        </div>
    </div>
</div>

<script>
function loginUser(){
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value;

    if(username === "" || password === ""){
        document.getElementById("msg").innerHTML = "<div class='msg error'>All fields are required</div>";
        return;
    }

    fetch("login_action.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ username: username, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === "success"){
            document.getElementById("msg").innerHTML = "<div class='msg success'>" + data.message + "</div>";
            setTimeout(() => window.location.href = "dashboard.php", 1000);
        } else {
            document.getElementById("msg").innerHTML = "<div class='msg error'>" + data.message + "</div>";
        }
    })
    .catch(error => {
        document.getElementById("msg").innerHTML = "<div class='msg error'>An error occurred. Please try again.</div>";
        console.log(error);
    });
}

// Allow Enter key to submit
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('password').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            loginUser();
        }
    });
});
</script>

</body>
</html>