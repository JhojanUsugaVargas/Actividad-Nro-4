<?php
$servername = "localhost"; // Especifica el puerto 3307
$username = "root";
$password = "1001139490";
$dbname = "rock_legends_store";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$name = $_POST['name'];
$city = $_POST['city'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$products = isset($_POST['products']) ? $_POST['products'] : [];

// Insertar datos del cliente en la tabla quotes
$sql = "INSERT INTO quotes (name, city, address, phone) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $city, $address, $phone);
$stmt->execute();
$quote_id = $stmt->insert_id;

// Insertar productos seleccionados
foreach ($products as $product_id) {
    $quantity = $_POST['quantity_' . $product_id];
    if ($quantity > 0) {
        $sql = "INSERT INTO quote_products (quote_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quote_id, $product_id, $quantity);
        $stmt->execute();
    }
}

$stmt->close();
$conn->close();

// Redirigir a la página de confirmación
header("Location: quote_success.php?quote_id=" . $quote_id);
exit();
?>