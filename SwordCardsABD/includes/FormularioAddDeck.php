<?php

class FormularioAddDeck extends Form {
 
    function __construct() {
        parent::__construct("añadir_deck",array());
    }


    protected function generaCamposFormulario($datosIniciales){
        return '  <div class="grupo-control">
        <label>Nombre:</label> <input type="text" name="nombreDeck" />
        </div>
        <div class="grupo-control">
        <label>Tamaño del Deck (entre 6 y 8):</label> <input type="number" name="tamaño" />
        </div>
        <div class="grupo-control"><button type="submit" name="addEnunciado" ">Crear</button></div>';	
    }
	
    protected function procesaFormulario($datos){
        $erroresFormulario = array();
        $nombreDeck = isset($_POST['nombreDeck']) ? $_POST['nombreDeck'] : null;
        if ( empty($nombreDeck) ) {
            $erroresFormulario[] = "El nombre del mazo no puede estar vacío";
        }

        $tamaño = isset($_POST['tamaño']) ? $_POST['tamaño'] : null;
        if($tamaño > 8 || $tamaño < 6){
            $erroresFormulario[] = "El tamaño es incorrecto";
        }
        $idUsuario = $_SESSION['id'];
        Deck::creaDeck($nombreDeck, $idUsuario, $tamaño);
        
        header('Location: menuCliente.php');
        exit();
    }
    
 }