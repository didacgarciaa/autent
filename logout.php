<?php
    session_start();
    session_unset();
    $cookie_sessio = session_get_cookie_params();
    setcookie("PHPSESSID","",time()-3600,$cookie_sessio['path'], $cookie_sessio['domain'], $cookie_sessio['secure'], $cookie_sessio['httponly']); //Neteja cookie de sessió
    session_destroy();
    header("Location: index.php");
?>