<?php

// ini_set('session.gc_maxlifetime', 31536000);

// session_set_cookie_params(31536000);

// session_start();


session_start();
$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    session_unset();
    session_destroy();
    session_start();
}

// either new or old, it should live at most for another year
$_SESSION['discard_after'] = $now + 31536000;
