<?php
class Deck
{
    private $idDeck;
    private $nombreDeck;
    private $idUsuario;
    private $winRate;
    private $numCartas;

    public function __construct($nombre, $idUser, $tamaño)
    {
        $this->nombreDeck = $nombre;
        $this->idUsuario = $idUser;
        $this->numCartas = $tamaño;
    }

    public static function creaDeck($nombreDeck, $idUsuario, $tamaño){
        $deck = self::buscaDeck($nombreDeck, $idUsuario);
        if ($deck) {
            return false;
        }
        $deck = new Deck($nombreDeck, $idUsuario, $tamaño);
        return self::guarda($deck);      
    }

    public static function buscaDeck($nombreDeck, $idUsuario)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM deck WHERE nombreDeck = '%s'", $conn->real_escape_string($nombreDeck));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $deck = new Deck($fila['nombreDeck'], $fila['idUsuario'], $fila['numCartas']);
                $deck->idDeck = $fila['idDeck'];
                $result = $deck;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }
    
    public static function guarda($deck)
    {
        if ($deck->idDeck !== null) {
            return self::actualiza($deck);
        }
      
        return self::inserta($deck);
    }
    
    private static function inserta($deck)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $usuario = $_SESSION['id'];
        $query=sprintf("INSERT INTO deck(nombreDeck, idUsuario, winRate, numCartas) VALUES ('%s', $usuario, 0, $deck->numCartas)"
        , $conn->real_escape_string($deck->nombreDeck));
        if ( $conn->query($query) ) {
            $deck->id = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $deck;
    }
     

    public function generaContenido($selection)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUser = $_SESSION['id'];
        $auxMedia = 0;
        if($selection == "'newDeck'"){
            $addDeck = new FormularioAddDeck();
			$addDeck->gestiona();
        }
        else if($selection == "'viewDeck'"){
            $query = sprintf("SELECT * FROM deck WHERE idUsuario=$idUser");
            $rs = $conn->query($query);
            if ($rs->num_rows > 0) {
                while($fila = $rs->fetch_assoc()){
                    echo "<div class=cartasTabla>
                    <div class=titulo>$fila[nombreDeck] </div>";
                    $listaCartas = sprintf("SELECT c.idCarta, c.nombreCarta, c.tipo, c.valor, cd.idDeck, c.winRate FROM cardsdeck cd, cards c WHERE cd.idDeck='.$fila[idDeck].' AND cd.idCarta=c.idCarta");
                    $rs2 = $conn->query($listaCartas);
                    if ($rs2->num_rows > 0) {
                        while($cartasDisponibles = $rs2->fetch_assoc()){
                            //$auxMedia+=$cartasDisponibles['winRate'];
                            echo "<div class=estadisticas>";
                            echo "<p>$cartasDisponibles[nombreCarta]</p>";
                            echo "<p>$cartasDisponibles[tipo]</p>";
                            echo "<p>$cartasDisponibles[valor]</p>";
                            echo "<p>$cartasDisponibles[winRate]</p>";
                            echo "</div>";
                            echo "<div class='borrarPractica'><a href = 'borrarCardMazo.php?idCarta=".$cartasDisponibles['idCarta']."&idDeck=".$cartasDisponibles['idDeck']."'>Eliminar Carta</a></div>";
                        }
                        $auxMedia = $auxMedia/$rs2->num_rows;
                      
                    }
                    echo "</div>";
                    echo "<p>WinRate: $auxMedia </p>";
                }
            }
            else{
                echo "No hay mazos";
            }
           
        }
    }

    public function addCarta($idCarta){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUser = $_SESSION['id'];
        $query = sprintf("SELECT * FROM deck WHERE idUsuario=$idUser");
        $rs = $conn->query($query);

        if($rs->num_rows > 0){
            echo '<form action="#" method="post">';
            echo '<select name="decks" size="5">';
            while($fila = $rs->fetch_assoc()) {
                if($fila['numCartas'] > self::compruebaTamaño($fila['idDeck'])){
                    echo "<option value='".$fila['idDeck']."'>".$fila['nombreDeck']."</option>";
                }
            }
            echo '</select></p>' ;
            echo '<p><input type = "submit" name="seleccionado" value="Insertar en Deck"> 
                    </form> </p>';

            if(isset($_POST['seleccionado'])){
                $mazoSelected = $_POST['decks'];
                self::insertCarta($mazoSelected, $idCarta);
            }
        }
        else{
            echo "<div class = cartasTabla><p>No hay decks disponibles.</p></div>";
        }        
    }

    private function insertCarta($idDeck, $idCarta){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $usuario = $_SESSION['id'];
        $query=sprintf("INSERT INTO cardsdeck(idDeck, idCarta) VALUES ($idDeck, $idCarta)");
        if (!$conn->query($query) ) {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        header('Location: menuCards.php');
    }


    public function borrarCarta($idCarta, $idDeck){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        echo $idCarta;
        echo $idDeck;
        $query=sprintf("DELETE FROM cardsdeck WHERE idDeck =$idDeck AND idCarta = $idCarta");
        if (!$conn->query($query) ) {
            echo "Error al eliminar de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
    }

    private function compruebaTamaño($idDeck){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idAdmin = $_SESSION['id'];
		$query = sprintf("  SELECT * FROM cardsdeck WHERE idDeck= $idDeck");
        $rs = $conn->query($query);
        return $rs->num_rows;
    }
}
