<?php


// TODO ENTERO
class FormularioModMatri extends Form {
 
    private $nombre;
    private $profesor;
    function __construct($nombre, $profesor) {
        $this->nombre = $nombre;
        $this->profesor = $profesor;
        parent::__construct("modificar_matriculado",array());
    }
    protected function generaCamposFormulario($datosIniciales){
		
        return '<p>
									Nombre: <input type="text" id="name" name="nombre" value ="'.$this->nombre.'" autofocus  />
									<br />
									Id profesor: <input type="text" name="profesor" value ="'.$this->profesor.'" autofocus />
									<br />
									
									<input type ="submit" id = "modf" value = "actualizar"> ';
    }
	
	public function getNombre(){
		return $this->nombre ; 
	}
	
    protected function procesaFormulario($datos){
        
        $erroresFormulario = array();
        
        $nombre= isset($datos['nombre']) ? $datos['nombre'] : null;
        
        if ( empty($nombre) || mb_strlen($nombre) < 5 ) {
            $erroresFormulario[] = "El nombre de la asignatura no puede tener menos de 5 carácteres";
        }
	
        if (count($erroresFormulario) === 0) {
            $admin= new Admin();
			
			//$admin->actualizarAsig($datos['nombre'] , $datos['profesor']); 
			
			echo "Se ha actualizado correctamente" ; 
			
			header('Location: menuCliente.php');
                exit();
        }
		
        return $erroresFormulario;
    }
    
 }