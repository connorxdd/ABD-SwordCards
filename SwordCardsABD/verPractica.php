<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <link rel="icon" type="image/png" href="images/icon.png" />
    <meta charset="utf-8">
    <script src="codemirror/lib/codemirror.js"></script>
    <link rel="stylesheet" href="codemirror/lib/codemirror.css">
    <script src="codemirror/mode/htmlmixed/htmlmixed.js"></script>
    <script src="codemirror/lib/codemirror.js"></script>
    <script src="codemirror/mode/xml/xml.js"></script>
    <script src="codemirror/mode/javascript/javascript.js"></script>
    <script src="codemirror/mode/css/css.js"></script>
    <script src="codemirror/mode/htmlmixed/htmlmixed.js"></script>
    <script src="codemirror/addon/edit/matchbrackets.js"></script>
    
    <title>CodRector</title>
</head>

<body>

    <div id="contenedor">
        <?php require("includes/comun/cabecera.php"); ?>
        <div id="contenido">
            <div id="codigo">
           <?php 
           if (isset($_GET['idPractica']) && isset($_GET['nombrePractica']) ) {
            $_SESSION['idPractica'] = $_GET['idPractica'];
            $_SESSION['nombrePractica'] = $_GET['nombrePractica'];
     }
           
           Practica::verPractica($_SESSION['idPractica'], $_SESSION['nombrePractica']);
           unset($_SESSION["idPractica"]);
           unset($_SESSION["nombrePractica"]);
           
           ?>
            </div>
        </div>
        <?php require("includes/comun/pie.php"); ?>
    </div>
<script>
     var editor = CodeMirror.fromTextArea(document.getElementById("scope"), {
      lineNumbers: true,
      smartIdent: true,
      tabSize: 5,
      readOnly: "nocursor",
      mode: "text/html",
      matchBrackets: true,
      autofocus: true
    });
    editor.setSize(750, 500);
</script>
</body>

</html>