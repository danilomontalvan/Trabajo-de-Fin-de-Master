<?php
// Datos de conexión a la base de datos
$serverName = "*****";
$database = "*****";
$username = "*****";
$password = "*****";


// Intenta establecer la conexión
try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar datos del formulario de inicio de sesión si se envió
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["usuario"]) && isset($_POST["contrasena"])) {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        try {
            $sql = "SELECT usuario FROM usuarios WHERE usuario = '". $usuario . "'AND contrasena = '" .$contrasena."' AND estado IS NULL" ;
            $stmt = $conn->prepare($sql);
            $stmt = $conn->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = $resultado['usuario'];
            if ($user != NULL) {
                // Usuario y contraseña válidos, redirigir a la página de inicio                
                $errorMensaje = "Bienvenido ".$user;
            
            } else {
                // Usuario o contraseña incorrectos, mostrar mensaje de error
                $errorMensaje = "Usuario o contraseña incorrectos.";
            }        
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }        
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php if (isset($errorMensaje)): ?>
        <p style="color: red;"><?php echo $errorMensaje; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>
        <br>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>
