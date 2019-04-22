<?php 

class UsuarioSW extends Usuario{
    public function __construct(){
        if (isset($_SESSION['nick'])) {
        }
        parent::__construct($_SESSION['nick'], '', '');
    }
    public function generaContenido(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUser = $_SESSION['id'];
        $result = false;
        /*$rs = $this->decks($idUser,$conn);
       
        if ($rs->num_rows > 0) {
            echo '<div class="contenidoAlumno">';
            while ($fila = $rs->fetch_assoc()) {
                echo "<div class='asignatura'>";
                echo "<div class='asignaturaTitulo'>".$fila['nombre']."</div>";
                $idAsignatura=$fila['idAsignatura'];
                $rs_temas = $this->temasPorAsignatura($idAsignatura,$conn);
                while($temas = $rs_temas->fetch_assoc()){
                    echo "<div class='tema'>".$temas['nombre']."</div>";
                    $idTema = $temas['idTema'];
                    $practicas = this.practicasPorTema($idTema,$conn);
                    while($practicasAlumnos = $practicas->fetch_assoc()){
                        echo "<div class='practicaAl'>".$practicasAlumnos['nombre']."</div>";
                    }
                    $enunciados = $this->enunciadosPorTema($idTema, $conn);
                    while($enunciadosAlumnos = $enunciados->fetch_assoc()){
                        echo "<div class='enunciado'>".$enunciadosAlumnos['nombre']."</div>";
                        $idEnunciado = $enunciadosAlumnos['idEnunciado'];
                        $practicas=$this->practicasPorEnunciados($idEnunciado,$conn);
                        if($practicas->num_rows == 0){
                            echo "<div class='crearPracticas'>";
                                echo "<a href = 'crearPractica.php?idEnunciado=$idEnunciado&idTema=$idTema'> AÑADIR PRÁCTICA </a>" ;
                                //echo "<div class='boton-practica'> <button type='submit' name='crear' a href='crearPractica.php?idEnunciado=".$practicas['idEnunciado']."'> CREAR PRACTICA </a></div>";
                            echo "</div>";
                        }
                        else{
                            while($practicasAlumnos = $practicas->fetch_assoc()){
                                $idPractica = $practicasAlumnos['idPractica'];
                                $nombrePractica=$practicasAlumnos['nombre'];
                                echo "<div class=menuPracticas>";
                                    echo "<div class='practicaAl'><a href = 'verPractica.php?idPractica=$idPractica&nombrePractica=$nombrePractica'> VER PRACTICA  </a></div>";
                                    echo "<div class='borrarPractica'><a href = 'borrarPractica.php?idPractica=$idPractica&idEnunciado=$idEnunciado'> BORRAR PRACTICA  </a></div>";
                                echo "</div>";
                                
                            }
                        }
                    }

                }
            
                echo "</div>";
            }
            echo '</div>';
            $rs->free();
        } else {
            echo "No tienes decks";
            exit();
        }*/
        return $result;
    }

    public function cardsList(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("  SELECT * 
                            FROM cards ");
        $rs = $conn->query($query);
        if ($rs->num_rows > 0) {
            while ($fila = $rs->fetch_assoc()) {
                echo "<div class='asignaturaTitulo'>".$fila['nombre']."</div>";
            }
        }
        else{
            echo "No hay cartas aún. Vuelva pronto";
        }
    }

    private function decks($idUser,$conn){
        $query = sprintf("  SELECT d.nombreDeck, d.winRate 
                            FROM deck d 
                            WHERE $idUser=d.idUsuario");
        $rs = $conn->query($query);
        return $rs;
    }
 


    private function temasPorAsignatura($idAsignatura,$conn){
        $query_temas=sprintf("  SELECT t.nombre,t.idTema 
                                FROM tema t 
                                WHERE t.idAsignatura =  $idAsignatura   ");
                    
        $rs_temas = $conn->query($query_temas);
        return $rs_temas;
    }


    private function practicasPorTema($idTema,$conn){
        $query_practicas = sprintf("    SELECT pr.nombre, pr.idPractica
                                        FROM practicas pr
                                        WHERE pr.idTema=$idTema");
        $practicas = $conn->query($query_practicas);
        return $practicas;
    }

    private function practicasPorEnunciados($idEnunciado,$conn){
        $query_practicas = sprintf("    SELECT pr.nombre, pr.idPractica, pr.idTema
                                        FROM practicas pr
                                        WHERE pr.idEnunciado=$idEnunciado");
        $practicas = $conn->query($query_practicas);
        return $practicas;
    }
    private function enunciadosPorTema($idTema,$conn){
        $query_enunciados = sprintf("   SELECT e.nombre,e.idEnunciado
                                        FROM enunciado e 
                                        WHERE e.idTema =  $idTema");
        $rs_enunciados = $conn->query($query_enunciados);
        return $rs_enunciados;
    }

   
    private function crearPractica($nombrePractica, $idEnunciado,$conn){
        $rs = practicasPorEnunciados($idEnunciado,$conn);
        if($rs->num_rows > 0){
            echo "Ya tiene una practica subida a este enunciado";
        }
        else{
            $id = this.id();
            $idTema = $rs['idTema'];
            $query=sprintf("INSERT INTO practicas(nombre, idAlumno, idTema, idEnunciado) VALUES($nombrePractica, $id, $idTema, $idEnunciado,)");
        }

    }
}

