<?php
$servername = "localhost";
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

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización Exitosa - Rock Legends Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }
        .success-message {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 50px auto;
        }
        .success-message h1 {
            color: #333;
        }
        .success-message p {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #ffcc00;
        }
    </style>
    <script>
        // Redirigir automáticamente al inicio después de 5 segundos
        setTimeout(function() {
            window.location.href = 'index.html';
        }, 5000);
    </script>
</head>
<body>
    <div class="success-message">
        <h1>¡Cotización Realizada Exitosamente!</h1>
        <p>Gracias, <?php echo htmlspecialchars($quote['name']); ?>, por tu solicitud de cotización.</p>
        <p>Serás redirigido al inicio en 5 segundos...</p>
        <a href="index.html" class="back-button">Volver al Inicio Ahora</a>
    </div>
</body>
</html>