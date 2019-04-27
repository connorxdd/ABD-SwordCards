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
				<div class="inicioAdmin">
					<?php $admin= new Admin(); ?>			
						<a href="ModCards.php?selection='addCard'"><button> Añadir Cartas </button></a>
						<a href="ModCards.php?selection='delCard'"><button> Eliminar Cartas </button></a>	
				</div>
					
			</div>

        <?php require("includes/comun/pie.php"); ?>
    </div>

</body>

</html> 
