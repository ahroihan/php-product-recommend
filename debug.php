<?php
require_once 'config/paths.php';

echo "<h1>URL Debug Information</h1>";

echo "<h2>Server Variables:</h2>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";

echo "<h2>Calculated Paths:</h2>";
echo "APP_ROOT: " . APP_ROOT . "<br>";
echo "APP_URL: " . APP_URL . "<br>";
echo "Base Path: " . parse_url(APP_URL, PHP_URL_PATH) . "<br>";

echo "<h2>URL Tests:</h2>";
echo "Home: " . url('home') . "<br>";
echo "Login: " . url('login') . "<br>";
echo "Cart: " . url('cart') . "<br>";
echo "CSS: " . asset('style.css') . "<br>";

echo "<h2>Current Path:</h2>";
echo "Current path: '" . current_path() . "'<br>";

echo "<h2>Links Test:</h2>";
echo '<a href="' . url('home') . '">Home</a><br>';
echo '<a href="' . url('login') . '">Login</a><br>';
echo '<a href="' . url('cart') . '">Cart</a><br>';
?>