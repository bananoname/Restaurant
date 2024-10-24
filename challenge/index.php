<?php
error_reporting(0);

require_once 'Helpers/CheckAuthentication.php';
require_once 'Helpers/ArrayHelpers.php';
require_once 'Models/IceCreamModel.php';
require_once 'Models/PizzaModel.php';
require_once 'Models/SpaghettiModel.php';
require_once 'Models/DatabaseModel.php';

use Helpers\ArrayHelpers;

isAuthenticated();
$username = $_SESSION['username'];
$id = $_SESSION['id'];
$db = new Database();
$orders = $db->getOrdersByUser($id);

// Vulnerable XML processing function (XXE injection possible)
if (isset($_POST['xml_input'])) {
    libxml_disable_entity_loader(false);
    $xml = simplexml_load_string($_POST['xml_input'], 'SimpleXMLElement', LIBXML_NOENT);
    $parsed_data = $xml->asXML();
    echo "Parsed XML Data: <br>" . htmlentities($parsed_data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Food</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body, html {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #ff7e5f, #feb47b);
    }

    .app {
      width: 100%;
      max-width: 900px;
      background-color: #fff;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      padding: 30px;
      text-align: center;
    }

    .order__container {
      margin-bottom: 30px;
    }

    .order__desc {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #ff7e5f;
    }

    .order__buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }

    .order__button {
      background-color: #ff7e5f;
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-size: 18px;
      transition: background 0.3s ease;
    }

    .order__button:hover {
      background-color: #feb47b;
    }

    .order__button img {
      width: 100px;
      height: auto;
      margin-bottom: 10px;
    }

    textarea {
      width: 100%;
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
      margin-bottom: 20px;
      transition: border-color 0.3s ease;
    }

    textarea:focus {
      border-color: #ff7e5f;
      outline: none;
    }

    button[type="submit"] {
      background-color: #ff7e5f;
      color: white;
      padding: 15px 25px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #feb47b;
    }

    .orders__list {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .order__item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
      font-size: 18px;
      color: #333;
    }

    .order__item img {
      width: 30px;
      height: 30px;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div class="app">
    <main>
      <!-- Greeting Section -->
      <section class="order">
        <div class="order__container">
          <div class="order__desc">Hello, <?php echo htmlentities($username); ?>! Place your food order</div>
          <div class="order__buttons">
            <form action="order.php" method="POST">
              <input type="hidden" name="data" value="<?php echo base64_encode(serialize(new Pizza())); ?>">
              <button type="submit" class="order__button">
                <img src="Static/Images/Pizza.gif" alt="Pizza">
                Order Pizza
              </button>
            </form>
            <form action="order.php" method="POST">
              <input type="hidden" name="data" value="<?php echo base64_encode(serialize(new IceCream())); ?>">
              <button type="submit" class="order__button">
                <img src="Static/Images/IceCream.gif" alt="Ice Cream">
                Order Ice Cream
              </button>
            </form>
            <form action="order.php" method="POST">
              <input type="hidden" name="data" value="<?php echo base64_encode(serialize(new Spaghetti())); ?>">
              <button type="submit" class="order__button">
                <img src="Static/Images/Spaghetti.gif" alt="Spaghetti">
                Order Spaghetti
              </button>
            </form>
          </div>
        </div>
      </section>

      <!-- XML Input Section -->
      <section class="order">
        <div class="order__container">
          <div class="order__desc">Submit XML Data (Vulnerable to XXE)</div>
          <form action="" method="POST">
            <textarea name="xml_input" rows="5" cols="30"></textarea>
            <button type="submit">Submit XML</button>
          </form>
        </div>
      </section>

      <!-- Orders Display Section -->
      <section class="order">
        <div class="order__container">
          <div class="order__desc">Your Orders</div>
          <div class="orders__list">
            <?php if (empty($orders)): ?>
              <p>You have no orders.</p>
            <?php else: ?>
              <?php foreach ($orders as $order): ?>
                <div class="order__item">
                  <img src="<?php echo "Static/Images/".$order['food_item'].".gif"; ?>" alt="<?php echo htmlspecialchars($order['food_item']); ?>">
                  Order ID: <?php echo htmlspecialchars($order['order_id']); ?> - Food Item: <?php echo htmlspecialchars($order['food_item']); ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
