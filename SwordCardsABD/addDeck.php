<?php 
require_once __DIR__ . '/includes/config.php';

if (isset($_GET['idCarta'])) {
    $_SESSION['idCarta'] = $_GET['idCarta'];
   
}
$cartaAñadida = Deck::addCarta( $_SESSION['idCarta']);  
header('Location: menuCards.php');