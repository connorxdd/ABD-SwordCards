<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <link rel="icon" type="image/png" href="images/icon.png" />
    <meta charset="utf-8">
    <title>CodRector</title>
</head>

<body>
        <?php 
        $aux='./uploads/enunciados/'.$_GET["idEnunciado"].'/'.$_GET["nombre"].'.pdf';
        echo "<iframe src=$aux></iframe>"
        ?>

</body>

</html> 