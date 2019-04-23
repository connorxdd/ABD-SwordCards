

<?php 
	
class Admin extends Usuario{
	
	
    public function __construct(){
        if (isset($_SESSION['nick'])) {
            echo "<script>console.log('paso')</script>";
        }
        parent::__construct($_SESSION['nick'], '', '');
    }
   
	
	public function addCard(){
		
	}

	public static function modCards($selection){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
		$idAdmin = $_SESSION['id'];	
		if(strcmp($selection, "'addCard'") == 0){
			$addCard = new FormularioAddCard();
			$addCard->gestiona();
		}	
		else if($selection=="'delCard'"){
			Cards::seleccionCard();
		}
		else if($selection=="'addProDeck'"){
			echo "ProDeck nuevo";
		}
    }
	
	
	// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	//                ESTRUTURA DE LA PAGINA
	// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	
	private function estructuraAsignaturas($idAdmin,$conn) {
		echo '<ul>';	
		echo '<li><button type="button" onclick = "location.href=\'borrAsigPrim.php\'"> Borrar asignatura </button></li>';
	    echo '<li><button type="button" onclick = "location.href=\'modAsigPrim.php \'"> Modificar asignatura </button></li>';
		echo '<li><button type="button" onclick = "location.href=\'crearAsig.php\'"> Crear asignatura </button></li>';
		echo '</ul>';				
	}
	
	private function estructuraMatriculados($idAdmin,$conn) {
		
		$this->modMatriculadosForm($idAdmin,$conn) ; 
		
	}
	
	// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	//                MODIFICAR / BORRAR Y CREAR ASIGNATURAS
	// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	
	// FUNCIONES GENERALES SOBRE ASIGNATURAS Y PROFESORES : 
	
	private function listaAsignaturas($idAdmin,$conn){ // TODO a.idAsignatura
        $query = sprintf("  SELECT  * FROM asignatura a ");
        $rs = $conn->query($query);
        return $rs;
    }
	
	public static function listaProfesores(){
		
		$app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idAdmin = $_SESSION['id'];
		
		$query = sprintf("  SELECT * FROM profesor p ,usuarios u WHERE u.idUsuario = p.idProfesor");
        $rs = $conn->query($query);
		
        return $rs;
		
	}
	
	
	// FORMULARIOS GENERALES
	
	public static function modAsignaturaForm() {
		
		$app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idAdmin = $_SESSION['id'];
		
		// 1- Obtenemos la lista de asignaturas
			$rs = Admin::listaAsignaturas($idAdmin,$conn);
		// 2- Mostramos las asignaturas en un select 
			if ($rs->num_rows > 0) {
				
				// Formulario modificar asignatura
					echo '<p><form method = "POST" id = "modAsig" action ="modificarAsig.php" >';
					echo '<div class = "inicioAdmin"><select name="asigs" size="5" >';
					while($fila = $rs->fetch_assoc()) {
						echo "<option>".$fila['nombre']."</option>";
					}
					echo '</select></div></p>' ; 
					echo '<div class="inicioAdmin">';
						echo '<button type = "submit"> modificar </button></form> ';
						echo '<button type="button" onclick = "location.href=\'menuCliente.php\'"> volver </button>'; 
					echo '</div>';
				$rs->free();
			} else {
				echo "No hay ninguna asignatura subida";
				exit();
			}

	}
	
	public static function borrarAsigForm () {
		
		$app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idAdmin = $_SESSION['id'];
		
		// 1- Obtenemos la lista de asignaturas
			$rs = Admin::listaAsignaturas($idAdmin,$conn);
		// 2- Mostramos las asignaturas en un select 
			if ($rs->num_rows > 0) {						          
					echo '<p><form method = "POST" id = "modAsig" action ="borrarAsig.php" >';
					echo '<select name="asigs" size="5" >';
					while($fila = $rs->fetch_assoc()) {
						echo "<option>".$fila['nombre']."</option>";
					}
					echo '</select></p>' ; 
					echo '<div class="inicioAdmin">';
						echo '<button type = "submit"> borrar </button> </form>';     
						echo '<button type="button" onclick = "location.href=\'menuCliente.php\'"> volver </button>'; 
					echo '</div>';
				$rs->free();
			} else {
				echo "No hay ninguna asignatura subida";
				exit();
			}
		
	}
	
	// BORRAR ASIGNATURA
	
	public function borrarAsig( $asig ){
		
		$app = Aplicacion::getSingleton();
			$conn = $app->conexionBd();
			$idAdmin = $_SESSION['id'];

		$id = $this->getIdFromAsig($asig, $idAdmin , $conn ) ; 
		echo "Asignatura : ". $asig . "id : " . $id . "<br/> " ; 
		
		
	
		Tema::borrarTemasUnaAsignatura($asig) ; 

		// BORRA LA ASIGNATURA 
		$upd = sprintf("DELETE FROM `asignatura` WHERE `asignatura`.`idAsignatura` = %d", $id);

		if ($conn->query($upd)){
				// Si todo va bien
				echo "Todo ha ido correctamente ..." ; 
		}
		else {
			echo "Error " . $conn->errno ;
			exit();
		}
	}
	
	// MODIFICAR ASIGNATURAS
 
	public function actualizarAsig($original, $nuevo, $profesor){
		
		$app = Aplicacion::getSingleton();
			$conn = $app->conexionBd();
			$idAdmin = $_SESSION['id'];
		echo $original . ' pasa a ' . $nuevo . "< br />" ; 
		$id = $this->getIdFromAsig($original, $idAdmin , $conn ) ; 
		echo "El id de asignatura es : " . $id . "< br />  y el profesor es : " . $profesor ;
		$id_prof = $this->getIdFromProf($profesor,$idAdmin,$conn); 
		
		$upd = sprintf("UPDATE `asignatura` SET `asignatura`.`nombre`='%s', `asignatura`.`idProfesor` = '%d' WHERE `asignatura`.`idAsignatura`='%d' ", 
			$conn	->real_escape_string($nuevo), 
			$id_prof, 
			$id);
			
		echo $upd ; 

		if ($conn->query($upd)){
				// Si todo va bien
				echo "Todo ha ido correctamente ..." ; 
		}
		else {
			echo "Error " . $conn->errno ;
			exit();
		}
	}
	
	// CREAR ASIGNATURAS
	
	public function crearAsig($nombre,$profesor){
		
		$app = Aplicacion::getSingleton();
			$conn = $app->conexionBd();
			$idAdmin = $_SESSION['id'];
		
		$id = $this->getIdFromAsig($nombre, $idAdmin , $conn ) ; 
		if ( $id != -1 ) {
			echo "La asignatura ya existe "; 
			header('Location: menuCliente.php');
			exit();
		}
		
		$id_prof = $this->getIdFromProf($profesor,$idAdmin,$conn); 
		
		echo "Profesor : " . $profesor ; //. "ID : " . $id_prof ; 
		if ( $id_prof ==  -1 ) {
			echo "No se encuentra el profesor "; 
			header('Location: menuCliente.php');
			exit();
		}
		$upd = sprintf("INSERT INTO `asignatura` (`idAsignatura`, `nombre`, `idProfesor`) VALUES (NULL, '%s', '%d') ",			
			$nombre, 
			$id_prof);
			
		echo "Profesor es : " . $profesor . $upd ; 

		if ($conn->query($upd)){
				// Si todo va bien
				echo "Todo ha ido correctamente ..." ; 
		}
		else {
			echo "Error " . $conn->errno ;
			exit();
		}
	}
	
	
	// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	//                MODIFICAR / BORRAR Y CREAR MATRICULADOS
	// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	
	private function alumnosMatriculados($idAdmin,$conn){ 
        $query = sprintf("  SELECT  * FROM alumnos_matriculados a, usuarios u
							WHERE a.idAlumno =u.idUsuario");
        $rs = $conn->query($query);
        return $rs;
    }
	
	private function modMatriculadosForm($idAdmin ,$conn) {
		
	
		// 1- Obtenemos la lista de asignaturas
			$rs = $this->alumnosMatriculados($idAdmin,$conn);
		// 2- Mostramos las asignaturas en un select 
			if ($rs->num_rows > 0) {
				
				echo '<p><form method = "POST" id = "modAsig" action ="modificarMatriculado.php?" >';
				echo '<select name="mats" size="5">';
				while($fila = $rs->fetch_assoc()) {
					echo "<option>".$fila['nick']."</option>";
				}
				echo '</select></p>' ;
				echo '<p><input type = "submit" value = "modificar"> </form> </p>';
				
				
				
				//TODO echo '<p><input type = "submit" value = "borrar"> </form> </p>';
				$rs->free();
			} else {
				echo "No hay ninguna asignatura subida";
				exit();
			}

	}
	
	
	
}

