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

$usuarios = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ou = $_POST['ou'];
    
    $ldap = new Ldap($opcions);
    $ldap->bind();
    
    // Base de recerca: especifiquem la unitat organitzativa
    $baseDnOu = "ou={$ou},$domini";
    // Filtre que recupera només les entrades que tenen tant inetOrgPerson com posixAccount
    $filter = '(& (objectClass=inetOrgPerson) (objectClass=posixAccount))';
    
    try {
        // Es realitza la cerca en tot l'arbre sota la OU especificada
        $results = $ldap->search($filter, $baseDnOu, Ldap::SEARCH_SCOPE_SUB);
        
        // Convertir resultats a array
        foreach ($results as $entry) {
            $usuarios[] = $entry;
        }
        
        if (empty($usuarios)) {
            $mensaje = "No s'han trobat usuaris a la unitat organitzativa: $ou";
        }
    } catch(Exception $e) {
        $mensaje = "Error en cercar usuaris: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Llistar Usuaris LDAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Llistar Usuaris LDAP</h2>
    <form method="POST" action="list.php" class="mb-4">
        <div class="mb-3">
            <label for="ou" class="form-label">Unitat Organitzativa (ou)</label>
            <input type="text" class="form-control" id="ou" name="ou" required>
        </div>
        <button type="submit" class="btn btn-primary">Cercar Usuaris</button>
    </form>
    
    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>
    
    <?php if(!empty($usuarios)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>DN</th>
                    <th>uid</th>
                    <th>ou</th>
                    <th>sn</th>
                    <th>uidNumber</th>
                    <th>gidNumber</th>
                    <th>homeDirectory</th>
                    <th>loginShell</th>
                    <th>cn</th>
                    <th>postalAddress</th>
                    <th>telephoneNumber</th>
                    <th>title</th>
                    <th>description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['dn'] ?></td>
                        <td><?= isset($usuario['uid'][0]) ? $usuario['uid'][0] : '' ?></td>
                        <td><?= isset($usuario['ou'][0]) ? $usuario['ou'][0] : '' ?></td>
                        <td><?= isset($usuario['sn'][0]) ? $usuario['sn'][0] : '' ?></td>
                        <td><?= isset($usuario['uidnumber'][0]) ? $usuario['uidnumber'][0] : '' ?></td>
                        <td><?= isset($usuario['gidnumber'][0]) ? $usuario['gidnumber'][0] : '' ?></td>
                        <td><?= isset($usuario['homedirectory'][0]) ? $usuario['homedirectory'][0] : '' ?></td>
                        <td><?= isset($usuario['loginshell'][0]) ? $usuario['loginshell'][0] : '' ?></td>
                        <td><?= isset($usuario['cn'][0]) ? $usuario['cn'][0] : '' ?></td>
                        <td><?= isset($usuario['postaladdress'][0]) ? $usuario['postaladdress'][0] : '' ?></td>
                        <td><?= isset($usuario['telephonenumber'][0]) ? $usuario['telephonenumber'][0] : '' ?></td>
                        <td><?= isset($usuario['title'][0]) ? $usuario['title'][0] : '' ?></td>
                        <td><?= isset($usuario['description'][0]) ? $usuario['description'][0] : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
	<a href="menu.php" class="btn btn-secondary mb-4">Tornar al menú</a>
</div>
</body>
</html>