<?php
// Configuración de la conexión a la base de datos Oracle
$host = '172.16.3.2';
$puerto = '1521';
$sid = 'ENKA';
$usuario = 'COMERCIAL';
$contrasena = '7J7H2M';

// Intentar establecer la conexión
$conn = oci_connect($usuario, $contrasena, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$puerto))(CONNECT_DATA=(SID=$sid)))");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Procesar el inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT usuario FROM vuln_user WHERE usuario = '".$username."' AND clave = '".$password."' AND estado IS NULL";    
    $stmt = oci_parse($conn, $sql);
    
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    $user = $row['USUARIO'];
	
    if ($user != null) {       
        session_start();
        $_SESSION['username'] = $user;
        $exito = "Bienvenido, ".$user;
    } else {
        // Credenciales inválidas
        $error = 'Credenciales inválidas. Inténtalo de nuevo.';
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
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>
 <?php if (isset($exito)) { 
	echo "Bienvenido, ".$user;
 } ?>
    <form method="POST" action="">
        <label for="username">Usuario:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>