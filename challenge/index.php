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

// Xử lý XML đầu vào (Submit XML) - Không ảnh hưởng đến đặt hàng
if (isset($_POST['xml_input']) && !empty($_POST['xml_input'])) {
  libxml_disable_entity_loader(false); // Cho phép thực thể bên ngoài (XXE), cần xử lý bảo mật ở môi trường thực tế
  $xml = simplexml_load_string($_POST['xml_input'], 'SimpleXMLElement', LIBXML_NOENT);
  if ($xml) {
      $parsed_data = $xml->asXML();
      echo "Parsed XML Data: <br>" . htmlentities($parsed_data);
  } else {
      echo "<script>alert('Invalid XML format!');</script>";
  }
}

// Xử lý đặt hàng (Order Food)
if (isset($_POST['data'])) {
  $food_item = unserialize(base64_decode($_POST['data']));
  if ($food_item) {
      // Thêm đơn hàng vào cơ sở dữ liệu
      $db->addOrder($id, get_class($food_item));
      echo "<script>alert('Order placed successfully!');</script>";
      // Cập nhật danh sách đơn hàng sau khi đặt hàng
      $orders = $db->getOrdersByUser($id);
  } else {
      echo "<script>alert('Invalid order data!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Food</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Silkscreen:wght@400;700&display=swap");

    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: "Silkscreen", sans-serif;
      color: #f0f0f0;
    }

    body, html {
      height: 100%;
      background-color: black;
    }

    .app {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-image: url('Static/Images/background.gif');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .order {
      width: 100%;
      max-width: 500px;
      background-color: rgba(0, 0, 0, 0.8);
      padding: 40px;
      border-radius: 20px;
      text-align: center;
      margin-bottom: 20px;
    }

    .order__desc {
      margin-bottom: 20px;
      font-size: 24px;
      text-transform: uppercase;
      color: #e0e0e0;
    }

    .order__buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .order__button {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px 20px;
      background-color: #af5865;
      color: #f0f0f0;
      cursor: pointer;
      border: none;
      border-radius: 5px;
      text-transform: uppercase;
      text-align: center;
    }

    .order__button img {
      width: 100px;
      height: auto;
      margin-bottom: 10px;
      border-radius: 5px;
    }

    .order__group > input {
      display: none;
    }

    .orders__list {
      margin-top: 20px;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      color: #f0f0f0;
    }

    .order__item {
      display: flex;
      align-items: center;
      color: #e0e0e0;
      margin-bottom: 10px;
      font-size: 18px;
    }

    .order__item img {
      width: 24px;
      height: 24px;
      margin-right: 10px;
    }

    /* Style for Submit XML button */
    form button[type="submit"] {
      background-color: #f0f0f0;
      color: black;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-transform: uppercase;
      font-size: 16px;
    }

    /* Style for textarea XML input */
    textarea[name="xml_input"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      color: black; /* Màu chữ đen */
      background-color: #f0f0f0; /* Nền xám nhạt */
      font-size: 16px;
      resize: none;
    }

    /* Style for checkboxes */
    input[type="checkbox"] {
      accent-color: #af5865;
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
