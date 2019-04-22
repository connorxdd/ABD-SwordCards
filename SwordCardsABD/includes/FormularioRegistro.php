<?php
class FormularioRegistro extends Form {
    function __construct() {
        parent::__construct("pregistro",array());
    }
    protected function generaCamposFormulario($datosIniciales){
        return '<div class="pregistro">
        <fieldset>
        <legend>Registro</legend>
        <div class="grupo-control">
            <label>Nick:</label> <input class="control" type="text" name="nick" />
        </div>
        <div class="grupo-control">
            <label>Nombre completo:</label> <input class="control" type="text" name="nombre" />
        </div>
        <div>
        <div class="grupo-control">
        <label>Rol:</label>
        <input class = "control" type="radio" name="rol" value="user">Usuario</input>
		<input class = "control" type="radio" name="rol" value="admin">Administrador</input><br>
        </div>
        <div class="grupo-control">
            <label>Password:</label> <input class="control" type="password" name="pwd" />
        </div>
        <div class="grupo-control"><label>Vuelve a introducir el Password:</label> <input class="control" type="password" name="password2" /><br /></div>
        <div class="grupo-control"><button type="submit" name="registro">Registrar</button></div>
    </fieldset>
    </div>';
    }
    protected function procesaFormulario($datos){
        if (! isset($_POST['registro']) ) {
            header('Location: registro.php');
            exit();
        }
        
        
        $erroresFormulario = array();
        
        $nombreUsuario = isset($_POST['nick']) ? $_POST['nick'] : null;
        
        if ( empty($nombreUsuario) || mb_strlen($nombreUsuario) < 5 ) {
            $erroresFormulario[] = "El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.";
        }

        $rol = isset($_POST['rol']) ? $_POST['rol'] : null;
        if ( empty($rol) ||(strcmp($rol, 'user' ) != 0) && (strcmp($rol, 'admin' ) != 0) ) {
            $erroresFormulario[] = "No se ha introducido ningun rol.";
        }
        
        $password = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        if ( empty($password) || mb_strlen($password) < 5 ) {
            $erroresFormulario[] = "El password tiene que tener una longitud de al menos 5 caracteres.";
        }
        $password2 = isset($_POST['password2']) ? $_POST['password2'] : null;
        if ( empty($password2) || strcmp($password, $password2) !== 0 ) {
            $erroresFormulario[] = "Los passwords deben coincidir";
        }
        
        if (count($erroresFormulario) === 0) {
            $usuario = Usuario::crea($nombreUsuario, $password, $rol);
            
            if (! $usuario ) {
                $erroresFormulario[] = "El usuario ya existe";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nick'] = $nombreUsuario;
                $_SESSION['pwd']=$password;
                $_SESSION['id']=$usuario->id();
                $_SESSION['esAdmin'] = strcmp($usuario->rol(), 'admin') == 0 ? true : false;
                header('Location: menuCliente.php');
                exit();
        
            }
        }
        return $erroresFormulario;
    }
 }