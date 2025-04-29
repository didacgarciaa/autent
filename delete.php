<?php
require 'vendor/autoload.php';
use Laminas\Ldap\Ldap;
session_start();

if (!isset($_SESSION['adm'])) {
    header('Location: login.php');
    exit;
}

ini_set('display_errors', 1);

$domini = 'dc=clotfje,dc=net';
$opcions = [
    'host'              => 'zend-digadu.clotfje.net',
    'username'          => "cn=admin,$domini",
    'password'          => 'fjeclot',
    'bindRequiresDn'    => true,
    'accountDomainName' => 'clotfje.net',
    'baseDn'            => $domini
];

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ou  = $_POST['ou'];
    $uid = $_POST['uid'];
    $dn = "uid={$uid},ou={$ou},$domini";
    
    $ldap = new Ldap($opcions);
    $ldap->bind();
    
    try {
        $ldap->delete($dn);
        $mensaje = "Usuari $uid eliminat correctament.";
    } catch(Exception $e) {
        $mensaje = "Error al eliminar l’usuari: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuari LDAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Eliminar Usuari LDAP</h2>
    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>
    <form method="POST" action="delete.php">
        <div class="mb-3">
            <label for="ou" class="form-label">Unitat Organitzativa (ou)</label>
            <input type="text" class="form-control" id="ou" name="ou" required>
        </div>
        <div class="mb-3">
            <label for="uid" class="form-label">Identificador de l’Usuari (uid)</label>
            <input type="text" class="form-control" id="uid" name="uid" required>
        </div>
        <button type="submit" class="btn btn-danger">Eliminar Usuari</button>
    </form>
    <a href="menu.php" class="btn btn-secondary mb-4">Tornar al menú</a>
</div>
</body>
</html>
