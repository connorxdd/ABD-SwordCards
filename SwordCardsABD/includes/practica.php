<?php
class Practica
{

    public static function buscaPractica($idTema, $idEnunciado)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $id = $_SESSION['id'];
        $query = sprintf("SELECT * FROM practicas P WHERE P.idAlumno = $id AND P.idTema = $idTema AND P.idEnunciado = $idEnunciado");
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $pract = new Practica($fila['nombre'], $fila['idAlumno'],$fila['idTema'], $fila['idEnunciado']);
                $pract->id = $fila['idPractica'];
                $result = $pract;
            }
            $rs->free();
        } 
        else {
            echo "1.Error al consultar la practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }
    
    public static function crea($nombrePractica, $idAlumno, $idTema, $idEnunciado)
    {
        $user = self::buscaPractica($idTema, $idEnunciado);
        if ($user) {
            return false;
        }
        if($idAlumno==$_SESSION['id']){
            $pract = new Practica($nombrePractica, $idAlumno, $idTema, $idEnunciado);
            return self::guarda($pract);
        }
        return false;
    }
    
    public static function guarda($pract)
    {
        if ($pract->idPractica !== null) {
            return self::actualiza($pract);
        }
        return self::inserta($pract);
    }
    
    private static function inserta($practica)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();

        $query=sprintf("INSERT INTO practicas(nombre, idAlumno, idTema, idEnunciado) VALUES('%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($practica->nombrePractica)
            , $conn->real_escape_string($practica->idAlumno)
            , $conn->real_escape_string($practica->idTema)
            , $conn->real_escape_string($practica->idEnunciado));
        if ( $conn->query($query) ) {
            $practica->idPractica = $conn->insert_id;
        } else {
            echo "2.Error al insertar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $practica;
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
    public static function borrar($idPractica, $idEnunciado)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idAlumno = $_SESSION['id'];
        $query=sprintf("DELETE FROM practicas WHERE idPractica = $idPractica AND idEnunciado = $idEnunciado AND idAlumno = $idAlumno");
        if ( $conn->query($query) ) {
            $dirPath='./uploads/practicas/'.$_GET["idPractica"].'/';
            self::deleteDir($dirPath);
            return true;
        } 
        else {
            echo "2.Error al borrar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return false;
    }
	
	public static function borrarPracticasUnTema($idTema)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idAlumno = $_SESSION['id']; 
		// Borra todas las practicas asociadas a un tema
			$query=sprintf("DELETE FROM practicas WHERE idTema = $idTema");
			if ( $conn->query($query) ) {
                $dirPath='./uploads/practicas/'.$_GET["idPractica"].'/';
              
				self::deleteDir($dirPath);
				return true;
			} 
			else {
				echo "2.Error al borrar practica en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
				exit();
			}
			return false;
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

    public static function verPractica($idPractica, $nombrePractica){
        
         $filename = "./uploads/practicas/$idPractica/$nombrePractica" ;
         $file = fopen( $filename, "r" );
         if( $file == false ) {
            echo ( "Error in opening file" );
            exit();
         }
         
         $filesize = filesize( $filename );
         $filetext = fread( $file, $filesize );
         fclose( $file );
         echo '<script>console.log()</script>';
         echo ( "<textarea id='scope'>$filetext</textarea>" );
    }
    

    public function generaContenido(){
        return '';
    }
    

    private $nombrePractica;

    private $idAlumno;

    private $idTema;

    private $idEnunciado;

    private $idPractica;


    private function __construct($nombrePractica, $idAlumno, $idTema, $idEnunciado)
    {
        $this->nombrePractica= $nombrePractica;
        $this->idAlumno = $idAlumno;
        $this->idTema=$idTema;
        $this->idEnunciado = $idEnunciado;
    }

    public function idPractica()
    {
        return $this->idPractica;
    }

    public function nombrePractica()
    {
        return $this->nombrePractica;
    }

    public function idAlumno()
    {
        return $this->idAlumno;
    }

    public function idTema(){
        return $this->idTema;
    }

    public function idEnunciado(){
        return $this->idEnunciado;
    }
}
