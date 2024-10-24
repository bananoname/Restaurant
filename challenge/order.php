<?php
error_reporting(0);
require_once 'Helpers/ArrayHelpers.php';
require_once 'Helpers/CheckAuthentication.php';
require_once 'Models/PizzaModel.php';
require_once 'Models/IceCreamModel.php';
require_once 'Models/SpaghettiModel.php';
require_once 'Models/DatabaseModel.php';

isAuthenticated();
$username = $_SESSION['username'];
$id = $_SESSION['id'];

$db = new Database();

// Lấy dữ liệu từ POST và giải mã
$order = unserialize(base64_decode($_POST['data']));

// Thay đổi phần xử lý XML để dễ bị XXE
$xmlString = $_POST['xml_data']; // Giả sử người dùng gửi XML thông qua POST
$dom = new DOMDocument();
$dom->loadXML($xmlString, LIBXML_NOENT | LIBXML_DTDLOAD); // Bật các thuộc tính dễ bị XXE

// Truy xuất dữ liệu từ XML (với lỗ hổng XXE)
$foodName = $dom->getElementsByTagName('food')->item(0)->nodeValue;

$result = $db->Order($id, $foodName);
if ($result) {
    header("Location: index.php");
    die();
} else {
    $errorInfo = $stmt->errorInfo();
    die("Error executing query: " . $errorInfo[2]);
}