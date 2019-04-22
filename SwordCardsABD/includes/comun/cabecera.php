<div id="cabecera">
 
    <div class="logoCabecera">
    
    <img src="images/SwordCards.png">
        <a href="menuCliente.php">
        <h1> SwordCards </h1>
        </a>
        
    </div>
    <?php
    if (isset($_SESSION["login"]) && ($_SESSION["login"] === true)) {
        echo "<div class='botonesLogin'>";
        echo "<form method='get' action='menuCliente.php'>";
        echo "<button type='submit'>" . $_SESSION['nick'] . "</button>";
        echo "</form>";
        echo "<form method='get' action='logout.php'>";
        echo "<button type='submit'>Cerrar Sesion</button>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<div class='botonesLogin'>";
        echo "<form method='get' action='login.php'>";
        echo "<button type='submit'>Login</button>";
        echo "</form>";
        echo "<form method='get' action='registro.php'>";
        echo "<button type='submit'>Registro</button>";
        echo "</form>";
        echo "</div>";
    }
    ?>
</div> 