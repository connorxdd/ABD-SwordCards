<?php
class Enunciado
{
    private $idTema;
    private $nombre;
    private $fechaIni;
    private $fechaFin;
    private $idEnunciado;

    public function __construct($idTema, $nombre,$fechaIni, $fechaFin)
    {   
        $this->idTema=$idTema;
        $this->nombre=$nombre;
        $this->fechaIni=$fechaIni;
        $this->fechaFin=$fechaFin;
    }

    public static function creaEnunciado($idTema,$nombreEnunciado, $fechaIni,$fechaFin){
        $enunciado = self::buscaEnunciado($nombreEnunciado);
        if ($enunciado) {
            return false;
        }
        $enunciado = new Enunciado($idTema,$nombreEnunciado, $fechaIni,$fechaFin);
        return self::guarda($enunciado);

    }
    public static function buscaEnunciado($nombreEnunciado)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM enunciado e,tema t WHERE e.nombre = '%s' AND e.idTema=".$_SESSION['idTema']." AND t.idTema=e.idTema", $conn->real_escape_string($nombreEnunciado));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $enunciado = new Enunciado($idTema, $nombre,$fechaIni, $fechaFin);
                $enunciado->id =  $fila['idEnunciado'];
                $result=$enunciado;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }
    
    public static function guarda($enunciado)
    {
        if ($enunciado->idEnunciado !== null) {
            return self::actualiza($enunciado);
        }
        return self::inserta($enunciado);
    }
    
    private static function inserta($enunciado)
    {   
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO enunciado(idTema,nombre,FechaInicio,FechaFin) VALUES($enunciado->idTema, '%s','%s','%s')"
        ,$conn->real_escape_string($enunciado->nombre)
        ,$enunciado->fechaIni
        ,$enunciado->fechaFin
        );
        if ( $conn->query($query) ) {
            $enunciado->idEnunciado = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $enunciado;
    }



    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        /*if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }*/
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
	
	public static function borrarEnunciadosUnTema($idTema){
		

        // Borra los enunciados asociados a un tema 
			$query=sprintf("DELETE FROM enunciado WHERE idTema = $idTema");
			if ( $conn->query($query) ) {
				 echo "Se han borrado correctamente los enunciados asociados a un tema "; 
			} 
			else {
				echo "2.Error al borrar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
				exit();
			}
    }

    public static function borrar($idEnunciado, $idTema, $nombreEnunciado){

        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $id = $_SESSION['id'];

        $query=sprintf("DELETE FROM enunciado WHERE idEnunciado = $idEnunciado AND idTema = $idTema");
        if ( $conn->query($query) ) {
            $dirPath='./uploads/enunciados/'.$_GET["idEnunciado"].'/';
            self::deleteDir($dirPath);
            return true;
        } 
        else {
            echo "2.Error al borrar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return false;
    }

    




    public function getId(){
        return $this->idEnunciado;
    }
    public function getNombre(){
        return $this->nombre;
    }
}
