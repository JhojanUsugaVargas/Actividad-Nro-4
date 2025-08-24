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

// Obtener el ID de la cotización
$quote_id = isset($_GET['quote_id']) ? intval($_GET['quote_id']) : 0;

// Obtener datos del cliente
$sql = "SELECT * FROM quotes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quote_id);
$stmt->execute();
$quote = $stmt->get_result()->fetch_assoc();

// Obtener productos seleccionados
$sql = "SELECT qp.product_id, qp.quantity, p.name, p.price 
        FROM quote_products qp 
        JOIN products p ON qp.product_id = p.id 
        WHERE qp.quote_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quote_id);
$stmt->execute();
$products = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Cotización - Rock Legends Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #ffcc00;
        }
    </style>
</head>
<body>
    <h1>Resultados de la Cotización</h1>
    <h3>Datos del Cliente</h3>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($quote['name']); ?></p>
    <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($quote['city']); ?></p>
    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($quote['address']); ?></p>
    <p><strong>Celular:</strong> <?php echo htmlspecialchars($quote['phone']); ?></p>

    <h3>Productos Seleccionados</h3>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($product = $products->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
            </tr>
            <?php $total += $product['price'] * $product['quantity']; ?>
        <?php } ?>
        <tr class="total">
            <td colspan="3">Total</td>
            <td>$<?php echo number_format($total, 2); ?></td>
        </tr>
    </table>

    <a href="index.html" class="back-button">Volver al Inicio</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>