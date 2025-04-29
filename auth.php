<?php
require 'vendor/autoload.php';
use Laminas\Ldap\Ldap;
ini_set('display_errors', 1);
session_start();

if (isset($_POST['adm'], $_POST['cts'])) {
    $opcions = [
        'host'               => 'zend-digadu.clotfje.net',
        'username'           => 'cn=admin,dc=clotfje,dc=net',
        'password'           => 'fjeclot',
        'bindRequiresDn'     => true,
        'accountDomainName'  => 'clotfje.net',
        'baseDn'             => 'dc=clotfje,dc=net',
    ];
    $ldap = new Ldap($opcions);
    
    $ctsnya = trim($_POST['cts']);
    
    try {
        $ldap->bind();
        
        $resultats = $ldap->search('(cn=' . trim($_POST['adm']) . ')', 'dc=clotfje,dc=net');
        $entrada = $resultats->getFirst();
        
        if ($entrada) {
            $dn = $entrada['dn'];         
            $ldap->bind($dn, $ctsnya);
            $_SESSION['adm'] = $_POST['adm'];
            header('Location: menu.php');
            exit;
        } else {
            $error = 'Usuari no trobat al directori LDAP';
        }
    } catch (\Laminas\Ldap\Exception\LdapException $e) {
        $error = 'Contrasenya incorrecta. LDAP bind failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>AUTENTICACIÓ AMB LDAP</title>
</head>
<body>
  <?php if (!empty($error)): ?>
    <p style="color:red;"><b><?= htmlspecialchars($error) ?></b></p>
  <?php endif; ?>

  <form action="" method="POST">
    Usuari admin LDAP: <input type="text" name="adm" required><br>
    Contrasenya:       <input type="password" name="cts" required><br>
    <button type="submit">Envia</button>
    <button type="reset">Neteja</button>
  </form>
</body>
</html>
