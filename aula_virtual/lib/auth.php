<?php
// Mínimo para pruebas locales
session_start();

function isLoggedIn() {
  return isset($_SESSION['user_id']);
}

// Para desarrollo, si no hay sesión, asumimos user_id=1
function currentUserId() {
  if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // DEMO
  }
  return $_SESSION['user_id'];
}
