<?php
require_once "Models/DatabaseModel.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $db = new Database();
        $db->username=$username;
        $db->password=$password;
        $result = $db->Register();
        if ($result) {
          header("Location: login.php");
          die();
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
  <title>Register</title>
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

    .register {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-image: url('Static/Images/background.gif');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .register__container {
      max-width: 300px;
      background-color: rgba(0, 0, 0, 0.7);
      padding: 40px;
      border-radius: 10px;
      text-align: center;
    }

    .register__desc {
      margin-bottom: 20px;
      font-size: 18px;
    }

    .register__group {
      margin-top: 20px;
    }

    .register__button {
      width: 100%;
      padding: 10px;
      background-color: #af5865;
      color: white;
      cursor: pointer;
      border: none;
      border-radius: 5px;
      text-transform: uppercase;
    }

    .register__group > input {
      width: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      padding: 10px;
      border: none;
      outline: none;
      border-radius: 5px;
      color: white;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="app">
    <main>
      <section class="register">
        <div class="register__container">
          <div class="register__desc">Register form</div>
          <form action="register.php" method="POST" class="register__group">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" class="register__button">Register</button>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
