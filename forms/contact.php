<?php
// Configuración de la conexión a la base de datos
$host = 'localhost'; // Servidor de base de datos
$dbname = 'moviecity'; // nombre de la base de datos
$username = 'root';
$password = '';

try {
    // Establecer la conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar el modo de errores de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];
    //echo $mensaje;

    // Validación simple
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Consulta para insertar los datos en la tabla 'correos'
        $sql = "INSERT INTO contacto (nombre, email, asunto, mensaje) 
        VALUES (:nombre, :email, :asunto, :mensaje)";

        // Preparar la consulta
        $stmt = $pdo->prepare($sql);

        // Asignar los valores a los parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':mensaje', $mensaje);
      

        // Ejecutar la consulta
        // Definir el mensaje predeterminado
        $mensaje_usuario = "Error al enviar mensaje";

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje_usuario = "Enviado";
        }

        // Redirigir con el mensaje a través de la URL
        header("Location: index.php?status=$mensaje_usuario#contact");
        -exit();  // Asegúrate de que no se ejecute código después de la redirección

    }
}