<?php
session_start();
function isLoggedIn(){ return isset($_SESSION['user_id']); }
function currentUserId(){ return $_SESSION['user_id'] ?? 1; }