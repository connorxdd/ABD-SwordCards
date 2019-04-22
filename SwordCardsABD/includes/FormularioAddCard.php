<?php

class FormularioAddCard extends Form {
 
    function __construct() {
        parent::__construct("añadir_carta",array('enctype'=>'multipart/form-data'));
    }


    protected function generaCamposFormulario($datosIniciales){
        return '  <div class="grupo-control">
        <label>Nombre:</label> <input type="text" name="nombreCard" />
        </div>
        <div class="grupo-control">
        <label>Tipo:</label> <input type="text" name="tipo" />
        </div>
        <div class="grupo-control">
        <label>Valor:</label> <input type="number" name="valor" />
        </div>
        <div class="grupo-control">
        <label>Descripcion:</label> <input type="text" name="descripcion" />
        </div>
        <div class="grupo-control">
        <label>Win Rate:</label> <input type="number" name="winRate" />
        </div>
        <div class="grupo-control">
        <label>Selecciona imagen: (png)</label> <input type="file" name="ruta" />
        </div>
        <div class="grupo-control"><button type="submit" name="addEnunciado" ">Crear</button></div>';	
    }
	
    protected function procesaFormulario($datos){
        $erroresFormulario = array();
        $dirAct=getcwd();
        $dirAct.='/uploads/images/';
        $target_file = $dirAct . basename($_FILES["ruta"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $nombreCarta = isset($_POST['nombreCard']) ? $_POST['nombreCard'] : null;
        $tipoCarta = isset($_POST['tipo']) ? $_POST['tipo'] : null;
        $valorCarta = isset($_POST['valor']) ? $_POST['valor'] : null;
        $descCarta = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
        $winRateCarta = isset($_POST['winRate']) ? $_POST['winRate'] : null;

        if(file_exists($target_file)){
            $erroresFormulario[] = "El archivo ya existe";
        }

        if($imageFileType != "png"){
            $erroresFormulario[] = "Solo se pueden subir archivos .png";
            $uploadOk = 0;
        }

        if($uploadOk == 0){
            $erroresFormulario[] = "No se ha podido subir la imagen";
        }
        else if(count($erroresFormulario) !== 0){
           
        }
        
        if (empty($nombreCarta)) {
            $erroresFormulario[] = "El nombre de la carta no puede estar vacío";
        }
        if (count($erroresFormulario) === 0) {
            $carta=Cards::creaCard($nombreCarta, $tipoCarta, $valorCarta, $descCarta, $winRateCarta);
            if(!$carta){
                $erroresFormulario[] = "Ya se ha creado esta carta";
            }      
            else{
                mkdir($dirAct.'/'.$carta->getId(),0777,true);
                if (!move_uploaded_file($_FILES['ruta']['tmp_name'], sprintf('%s/%s/%s.%s',$dirAct, $carta->getId(), $carta->getId(),'png') )){
                    $erroresFormulario = "The file ". basename( $_FILES["ruta"]["name"]). " se ha subido.";
                } 
                else {
                    header('Location: menuCliente.php');
                    exit();
                }
            }          
        }       
        return $erroresFormulario;
        
    }
 }