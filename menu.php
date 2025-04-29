<?php

session_start();
if (!isset($_SESSION['adm'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú CRUD de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="list-group mb-4">
            <a class="list-group-item list-group-item-action" href="create.php">Crear Usuari</a>
            <a class="list-group-item list-group-item-action" href="list.php">Llistar Usuaris</a>
            <a class="list-group-item list-group-item-action" href="update.php">Actualitzar Usuari</a>
            <a class="list-group-item list-group-item-action" href="delete.php">Eliminar Usuari</a>
            <a class="list-group-item list-group-item-action" href="logout.php">Tancar Sessió</a>
        </div>
        
        <h1>Benvingut, <?php echo $_SESSION['adm']; ?></h1>
        <p>Seleccioni una opció del menú per gestionar els usuaris.</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
