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
$usuariTrobat = false;
$usuariDades = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['buscar'])) {
        $ou  = $_POST['ou'];
        $uid = $_POST['uid'];
        $dn = "uid={$uid},ou={$ou},$domini";
        
        $ldap = new Ldap($opcions);
        $ldap->bind();
        
        try {
            $usuariDades = $ldap->getEntry($dn);
            $usuariTrobat = true;
        } catch(Exception $e) {
            $mensaje = "Usuari no trobat: " . $e->getMessage();
        }
    } elseif(isset($_POST['actualitzar'])) {
        $ou  = $_POST['ou'];
        $uid = $_POST['uid'];
        $dn = "uid={$uid},ou={$ou},$domini";
        
        $entrada = [];
        if(isset($_POST['uidNumber'])){
            $entrada['uidNumber']= $_POST['uidNumber']; 
        }
        if(isset($_POST['gidNumber'])){ 
            $entrada['gidNumber']= $_POST['gidNumber']; 
        }
        if(isset($_POST['homeDirectory'])){ 
            $entrada['homeDirectory']   = $_POST['homeDirectory']; 
        }
        if(isset($_POST['loginShell'])){ 
            $entrada['loginShell']= $_POST['loginShell']; 
        }
        if(isset($_POST['cn'])){ 
            $entrada['cn']= $_POST['cn']; 
        }
        if(isset($_POST['postalAddress'])){ 
            $entrada['postalAddress']= $_POST['postalAddress']; 
        }
        if(isset($_POST['telephoneNumber'])){ 
            $entrada['telephoneNumber']= $_POST['telephoneNumber']; 
        }
        if(isset($_POST['title'])){ 
            $entrada['title']= $_POST['title']; 
        }
        if(isset($_POST['description'])){ 
            $entrada['description']= $_POST['description']; 
        }
        if(!empty($_POST['userPassword'])){ 
            $entrada['userPassword']= $_POST['userPassword']; 
        }
        
        $ldap = new Ldap($opcions);
        $ldap->bind();
        
        try {
            $ldap->update($dn, $entrada);
            $mensaje = "Usuari actualitzat correctament.";
            $usuariTrobat = false;
        } catch(Exception $e) {
            $mensaje = "Error en actualitzar l’usuari: " . $e->getMessage();
            $usuariTrobat = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Actualitzar Usuari LDAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Actualitzar Usuari LDAP</h2>
    
    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>
    
    <?php if(!$usuariTrobat): ?>
    <form method="POST" action="update.php" class="mb-4">
        <div class="mb-3">
            <label for="ou" class="form-label">Unitat Organitzativa (ou)</label>
            <input type="text" class="form-control" id="ou" name="ou" required>
        </div>
        <div class="mb-3">
            <label for="uid" class="form-label">Identificador de l’Usuari (uid)</label>
            <input type="text" class="form-control" id="uid" name="uid" required>
        </div>
        <button type="submit" name="buscar" class="btn btn-primary">Cercar Usuari</button>
    </form>
    <?php endif; ?>
    
    <?php if($usuariTrobat): ?>
    <form method="POST" action="update.php">
        <input type="hidden" name="ou" value="<?= $_POST['ou'] ?>">
        <input type="hidden" name="uid" value="<?= $_POST['uid'] ?>">
        <div class="mb-3">
            <label for="uidNumber" class="form-label">Número Identificador de l’Usuari (uidNumber)</label>
            <input type="number" class="form-control" id="uidNumber" name="uidNumber" value="<?= isset($usuariDades['uidNumber'][0]) ? $usuariDades['uidNumber'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="gidNumber" class="form-label">Número Identificador del Grup (gidNumber)</label>
            <input type="number" class="form-control" id="gidNumber" name="gidNumber" value="<?= isset($usuariDades['gidNumber'][0]) ? $usuariDades['gidNumber'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="homeDirectory" class="form-label">Directori Personal (homeDirectory)</label>
            <input type="text" class="form-control" id="homeDirectory" name="homeDirectory" value="<?= isset($usuariDades['homeDirectory'][0]) ? $usuariDades['homeDirectory'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="loginShell" class="form-label">Shell (loginShell)</label>
            <input type="text" class="form-control" id="loginShell" name="loginShell" value="<?= isset($usuariDades['loginShell'][0]) ? $usuariDades['loginShell'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="cn" class="form-label">Nom Complet (cn)</label>
            <input type="text" class="form-control" id="cn" name="cn" value="<?= isset($usuariDades['cn'][0]) ? $usuariDades['cn'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="postalAddress" class="form-label">Adreça Física (postalAddress)</label>
            <input type="text" class="form-control" id="postalAddress" name="postalAddress" value="<?= isset($usuariDades['postalAddress'][0]) ? $usuariDades['postalAddress'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="telephoneNumber" class="form-label">Telèfon (telephoneNumber)</label>
            <input type="text" class="form-control" id="telephoneNumber" name="telephoneNumber" value="<?= isset($usuariDades['telephoneNumber'][0]) ? $usuariDades['telephoneNumber'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Títol (title)</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= isset($usuariDades['title'][0]) ? $usuariDades['title'][0] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripció (description)</label>
            <textarea class="form-control" id="description" name="description"><?= isset($usuariDades['description'][0]) ? $usuariDades['description'][0] : '' ?></textarea>
        </div>
        <div class="mb-3">
            <label for="userPassword" class="form-label">Nova Contrasenya (si es vol canviar)</label>
            <input type="password" class="form-control" id="userPassword" name="userPassword">
        </div>
        <button type="submit" name="actualitzar" class="btn btn-success">Actualitzar Usuari</button>
    </form>
    <?php endif; ?>
    <a href="menu.php" class="btn btn-secondary mb-4">Tornar al menú</a>
</div>
</body>
</html>
