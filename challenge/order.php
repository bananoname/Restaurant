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

$order = unserialize(base64_decode($_POST['data']));

$foodName = get_class($order);

$result = $db->Order($id,$foodName);
if ($result) {
    header("Location: index.php");
    die();
} else {
    $errorInfo = $stmt->errorInfo();
    die("Error executing query: " . $errorInfo[2]);
}