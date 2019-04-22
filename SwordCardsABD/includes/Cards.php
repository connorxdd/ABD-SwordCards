<?php
class Cards
{
    private $idCarta;
    private $nombreCard;
    private $tipo;
    private $valor;
    private $descripcion;
    private $winRate;

    private function __construct($nombreCard, $tipo, $value, $descripcion, $wR){
        $this->nombreCard= $nombreCard;
        $this->tipo = $tipo;
        $this->valor = $value;
        $this->descripcion = $descripcion;
        $this->winRate = $wR;
    }

    public static function creaCard($nombreCard, $tipoCarta, $valorCarta, $descCarta, $winRateCarta){
        $carta = self::buscaCard($nombreCard);
        if ($carta) {
            return false;
        }
        $carta = new Cards($nombreCard, $tipoCarta, $valorCarta, $descCarta, $winRateCarta);
        return self::guarda($carta);
    }

    public static function buscaCard($nombreCard){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM cards c WHERE c.nombreCarta='%s'", $conn->real_escape_string($nombreCard));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $card = new Cards($fila['nombreCarta'], $fila['tipo'],$fila['valor'], $fila['descripcion'], $fila['winRate']);
                $card->id = $fila['idCarta'];
                $result = $card;
            }
            $rs->free();
        } 
        else {
            echo "1.Error al consultar la practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }
    
 
    
    public static function guarda($carta)
    {
        if ($carta->idCarta !== null) {
            return self::actualiza($carta);
        }
        return self::inserta($carta);
    }
    
    private static function inserta($carta)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();

        $query=sprintf("INSERT INTO cards(nombreCarta, tipo, valor, descripcion, winRate) 
                        VALUES('%s', '%s', '%d', '%s', '%d')"
                        ,$conn->real_escape_string($carta->nombreCard)
                        ,$conn->real_escape_string($carta->tipo)
                        ,$carta->valor
                        ,$conn->real_escape_string($carta->descripcion)
                        ,$carta->winRate
                    );
        if ( $conn->query($query) ) {
            $carta->idCarta = $conn->insert_id;
        } else {
            echo "2.Error al insertar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $carta;
    }

    public static function seleccionCard()
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("SELECT * FROM cards");
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {                
            while($fila = $rs->fetch_assoc()){
                echo "<div class=cartasTabla>
                    <div class=estadisticas>
                        <p>Nombre: ".$fila['nombreCarta']."</p>
                        <p>Tipo: ".$fila['tipo']."</p>
                        <p>Valor: ".$fila['valor']."</p>
                        <p>Descripción: ".$fila['descripcion']."</p>
                        <p> WinRate: ".$fila['winRate']."</p>
                        <div class='añadirTema'>
                        <a href='borrarCard.php?idCarta=".$fila['idCarta']."&nombreCarta=".$fila['nombreCarta']."'>
                            Eliminar carta</a>
                        </div>
                        </div>
                    </div>";
            }
        } 
        return false;
    }


    public function borrarCard($idCarta, $nombreCarta){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("DELETE FROM cards WHERE idCarta=$idCarta");
        if ( $conn->query($query) ) {
            return true;
        } 
        else {
            echo "2.Error al borrar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return false;
    }


    public function generaContenido(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("SELECT * FROM cards");
        $rs = $conn->query($query);
        $result = false;
        $dirAct = getcwd();
        if ($rs) {                
            while($fila = $rs->fetch_assoc()){
                echo "<div class=cartasTabla>
                        <div class=estadisticas>
                        <p>Nombre: ".$fila['nombreCarta']."</p>
                        <p>Tipo: ".$fila['tipo']."</p>
                        <p>Valor: ".$fila['valor']."</p>
                        <p>Descripción: ".$fila['descripcion']."</p>
                        <p> WinRate: ".$fila['winRate']."</p>
                        <img src='$dirAct/uploads/images/".$fila['idCarta']."/".$fila['idCarta'].".png' width=280 height=125 title=Logo of a company alt=Logo of a company/>
                        <a href='modDeck.php?idCard=".$fila['idCarta']."&nombreCarta=".$fila['nombreCarta']."'>
                            Añadir a mazo</a>
                        </div>
                        
                    </div>";
            }
        } 
        else {
            echo "No hay cartas disponibles aún";
        }
        return $result;
    }


    
    private static function actualiza($practica)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE practicas U SET nombre = '%s', idAlumno='%s', idTema='%s', idEnunciado='%s' WHERE U.idPractica=%i"
            , $conn->real_escape_string($practica->nombreUsuario)
            , $conn->real_escape_string($practica->nombre)
            , $conn->real_escape_string($practica->password)
            , $conn->real_escape_string($practica->rol)
            , $practica->idPractica);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                echo "No se ha podido actualizar la practica: " . $practica->id;
                exit();
            }
        } else {
            echo "3.Error al insertar la practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        
        return $practica;
    } 


    public function getId(){
        return $this->idCarta;
    }

    public function getNombre(){
        return $this->nombreCard;
    }
}
