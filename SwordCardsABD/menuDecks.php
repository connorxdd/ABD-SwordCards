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
                Deck::generaContenido($_GET['selection']);
                ?>
        </div>

        <?php require("includes/comun/pie.php"); ?>
    </div>

</body>

</html> 