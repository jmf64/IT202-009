<?php
//starts/loads a session, basically tells php to do its magic
session_start();



// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>
