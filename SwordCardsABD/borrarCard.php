<?php 
require_once __DIR__ . '/includes/config.php';

if (isset($_GET['idCarta'])) {
    $_SESSION['idCarta'] = $_GET['idCarta'];
    $_SESSION['idDeck'] = $_GET['idDeck'];
}

$DeckCard = Deck::borrarCarta($_SESSION['idCarta'], $_SESSION['idDeck']); 
unset($_SESSION["idCarta"]);
unset($_SESSION['nombreCarta']);
header('Location: menuCliente.php');