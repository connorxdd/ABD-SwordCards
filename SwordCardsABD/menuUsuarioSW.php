<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <link rel="icon" type="image/png" href="images/icon.png" />
    <meta charset="utf-8">
	
    <title>SwordCards</title>
	
</head>

<body>

    <div id="contenedor">
        <?php require("includes/comun/cabecera.php"); ?>
        <div id="contenido">
                <?php
                    $usuariosw = new UsuarioSW();
                    $usuariosw->generaContenido();
                ?>

            <div class=inicioAdmin>
                <a href='menuCards.php'><button>Ver cartas</button></a>
                <a href="menuDecks.php?selection='newDeck'"><button>Diseñar Deck</button></a>
                <a href="menuDecks.php?selection='viewDeck'"><button>Mis Decks</button></a>
            </div>
        </div>

        <?php require("includes/comun/pie.php"); ?>
    </div>

</body>

</html> 