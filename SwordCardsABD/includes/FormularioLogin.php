<?php

class FormularioLogin extends Form {
    function __construct() {
        parent::__construct("plogin",array());
    }
    protected function generaCamposFormulario($datosIniciales){
        return '<div class="pregistro">
        <fieldset>
        <legend>Usuario y contraseña</legend>
        <div class="grupo-control">
            <label>Nick:</label> <input type="text" name="nick" />
        </div>
        <div class="grupo-control">
            <label>Password:</label> <input type="password" name="password" />
        </div>
        <div class="grupo-control"><button type="submit" name="login">Entrar</button></div>
    </fieldset>
    </div>';
    }
    protected function procesaFormulario($datos){
        if (! isset($datos['login']) ) {
            header('Location: login.php');
            exit();
        }
        $erroresFormulario = array();
        
        $nombreUsuario = isset($datos['nick']) ? $datos['nick'] : null;
        
        if ( empty($nombreUsuario) ) {
            $erroresFormulario[] = "El nombre de usuario no puede estar vacío";
        }
        
        $password = isset($datos['password']) ? $datos['password'] : null;
        if ( empty($password) ) {
            $erroresFormulario[] = "El password no puede estar vacío.";
        }
        
        if (count($erroresFormulario) === 0) {
            $usuario = Usuario::buscaUsuario($nombreUsuario);
            if (!$usuario) {
                $erroresFormulario[] = "El usuario o el password no coinciden";
            } else {
                if ( $usuario->compruebaPassword($password) ) {
                    $_SESSION['login'] = true;
                    $_SESSION['nick'] = $nombreUsuario;
                    $_SESSION['pwd']=$password;
                    $_SESSION['id']=$usuario->id();
                    $_SESSION['esAdmin'] = strcmp($usuario->rol() , 'admin') == 0 ? true : false;
                    header('Location: menuCliente.php');
                    exit();
                } else {
                    $erroresFormulario[] = "El usuario o el password no coinciden";
                }
            }
        }
        return $erroresFormulario;
    }
    
 }