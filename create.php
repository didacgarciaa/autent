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
    $ou              = $_POST['ou'];
    $sn              = $_POST['sn'];
    $uid             = $_POST['uid'];
    $uidNumber       = $_POST['uidNumber'];
    $gidNumber       = $_POST['gidNumber'];
    $homeDirectory   = $_POST['homeDirectory'];
    $loginShell      = $_POST['loginShell'];
    $cn              = $_POST['cn'];
    $postalAddress   = $_POST['postalAddress'];
    $telephoneNumber = $_POST['telephoneNumber'];
    $title           = $_POST['title'];
    $description     = $_POST['description'];
    $userPassword    = $_POST['userPassword'];
    
    $ldap = new Ldap($opcions);
    $ldap->bind();
    
    $dn = "uid={$uid},ou={$ou},$domini";
    
    $entrada = [
        'objectClass'     => ['top', 'posixAccount', 'inetOrgPerson'],
        'uid'             => $uid,
        'ou'              => $ou,
        'sn'              => $sn,
        'uidNumber'       => $uidNumber,
        'gidNumber'       => $gidNumber,
        'homeDirectory'   => $homeDirectory,
        'loginShell'      => $loginShell,
        'cn'              => $cn,
        'postalAddress'   => $postalAddress,
        'telephoneNumber' => $telephoneNumber,
        'title'           => $title,
        'description'     => $description,
        'userPassword'    => $userPassword
    ];

    try {
        $ldap->add($dn, $entrada);
        $mensaje = "Usuari $uid creat correctament.";
    } catch(Exception $e) {
        $mensaje = "Error al crear l’usuari: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuari LDAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Crear Usuari LDAP</h2>
    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>
    <form method="POST" action="create.php">
        <div class="mb-3">
            <label for="ou" class="form-label">Unitat Organitzativa (ou)</label>
            <input type="text" class="form-control" id="ou" name="ou" required>
        </div>
        <div class="mb-3">
            <label for="ou" class="form-label">SN</label>
            <input type="text" class="form-control" id="sn" name="sn" required>
        </div>
        <div class="mb-3">
            <label for="uid" class="form-label">Identificador de l’Usuari (uid)</label>
            <input type="text" class="form-control" id="uid" name="uid" required>
        </div>
        <div class="mb-3">
            <label for="uidNumber" class="form-label">Número Identificador de l’Usuari (uidNumber)</label>
            <input type="number" class="form-control" id="uidNumber" name="uidNumber" required>
        </div>
        <div class="mb-3">
            <label for="gidNumber" class="form-label">Número Identificador del Grup (gidNumber)</label>
            <input type="number" class="form-control" id="gidNumber" name="gidNumber" required>
        </div>
        <div class="mb-3">
            <label for="homeDirectory" class="form-label">Directori Personal (homeDirectory)</label>
            <input type="text" class="form-control" id="homeDirectory" name="homeDirectory" required>
        </div>
        <div class="mb-3">
            <label for="loginShell" class="form-label">Shell (loginShell)</label>
            <input type="text" class="form-control" id="loginShell" name="loginShell" required>
        </div>
        <div class="mb-3">
            <label for="cn" class="form-label">Nom Complet (cn)</label>
            <input type="text" class="form-control" id="cn" name="cn" required>
        </div>
        <div class="mb-3">
            <label for="postalAddress" class="form-label">Adreça Física (postalAddress)</label>
            <input type="text" class="form-control" id="postalAddress" name="postalAddress">
        </div>
        <div class="mb-3">
            <label for="telephoneNumber" class="form-label">Telèfon (telephoneNumber)</label>
            <input type="text" class="form-control" id="telephoneNumber" name="telephoneNumber">
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Títol (title)</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripció (description)</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="userPassword" class="form-label">Contrasenya</label>
            <input type="password" class="form-control" id="userPassword" name="userPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Usuari</button>
    </form>
    <a href="menu.php" class="btn btn-secondary mb-4">Tornar al menú</a>
</div>
</body>
</html>
