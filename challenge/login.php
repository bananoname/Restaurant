<?php
require_once "Models/DatabaseModel.php";

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $db = new Database();
        $db->username=$username;
        $db->password=$password;
        $result = $db->Login();
        if ($result) {
            session_start();
            $_SESSION['id']=$result[0]['id'];
            $_SESSION['username'] = $result[0]['username'];;
            header("Location: index.php");
            exit;
        } else {
            echo "Login failed.";            
        }
    } else {
        echo "Username and Password are required.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Form</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Silkscreen:wght@400;700&display=swap");

    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: "Silkscreen", sans-serif;
      color: white;
    }

    body, html {
      height: 100%;
      background-color: black;
    }

    .login {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-image: url('Static/Images/background.gif');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .login__container {
      max-width: 300px;
      background-color: rgba(0, 0, 0, 0.7);
      padding: 40px;
      border-radius: 10px;
      text-align: center;
    }

    .login__desc {
      margin-bottom: 20px;
      font-size: 18px;
    }

    .login__group {
      margin-top: 20px;
    }

    .login__button {
      width: 100%;
      padding: 10px;
      background-color: #af5865;
      color: white;
      cursor: pointer;
      border: none;
      border-radius: 5px;
      text-transform: uppercase;
    }

    .login__group > input {
      width: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      padding: 10px;
      border: none;
      outline: none;
      border-radius: 5px;
      color: white;
      margin-bottom: 10px;
    }

    .login__register {
      margin-top: 10px;
      font-size: 14px;
    }

    .login__register a {
      color: #af5865;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="app">
    <main>
      <section class="login">
        <div class="login__container">
          <div class="login__desc">Login to continue</div>
          <form action="login.php" method="POST" class="login__group">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" class="login__button">Login</button>
          </form>
          <div class="login__register">
            <span>Don't have an account? <a href="register.php">Register here</a></span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>

