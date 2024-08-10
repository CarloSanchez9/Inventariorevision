<?php
    require_once "../inc/session_star.php";

    require_once "main.php";

    $id=limpiar_cadena($_POST['usuario_id']);

    # Verificando el usuario #

    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

    if($check_usuario->rowCount()<0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Usuario no se encuentra en la busqueda
            </div> 
        ';
        exit();
    }else{
        $datos=$check_usuario->fetch();
    }
    $check_usuario=null;

    $admin_usuario=limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave=limpiar_cadena($_POST['administrador_clave']);

    # Verificacion de campos obligatorios #
    if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" ||
    $clave_2==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se han llenado todos los campos que son obligatorios a usuario y clave
            </div> 
        ';
        exit();
    }

    # Verificacion de Integridad de los datos #
    if(vericar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su usuario no coincide con el formato solicitado
            </div> 
        ';
        exit();
    }

    if(vericar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su clave no coincide con el formato solicitado
            </div> 
        ';
        exit();
    }

    # Verificando admin #
    $check_admin=conexion();
    $check_admin=$check_admin->query("SELECT usuario_usuario,usuario_clave FROM
    usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id'].
    "'");
    
    if($check_admin->rowCount()==1){
        $check_admin=$check_admin->fetch();

        if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify ($admin_clave,$check_admin['usuario_clave'])){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Usuario o clave de administrador incorrectos
            </div> 
        ';
        exit();

        }

    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Usuario o clave de administrador incorrectos
            </div> 
        ';
        exit();

    }
    $check_admin=null;

    # Almacenamiento de datos #
    $nombre=limpiar_cadena($_POST['usuario_nombre']);
    $apellido=limpiar_cadena($_POST['usuario_apellido']);

    $usuario=limpiar_cadena($_POST['usuario_usuario']);
    $email=limpiar_cadena($_POST['usuario_email']);

    $clave_1=limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2=limpiar_cadena($_POST['usuario_clave_2']);

    # Verificacion de campos obligatorios #
    if($nombre=="" || $apellido=="" || $usuario=="" ){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se han llenado todos los campos que son obligatorios
            </div> 
        ';
        exit();
    }

    # Verificacion de Integridad de los datos #
    if(vericar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Nombre no coincide con el formato solicitado
            </div> 
        ';
        exit();
    }

    if(vericar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Apellido no coincide con el formato solicitado
            </div> 
        ';
        exit();
    }

    if(vericar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Usuario no coincide con el formato solicitado
            </div> 
        ';
        exit();
    }

    # Verificando email #
    if($email!="" && $email!=$datos['usuario_email']){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email FROM usuario
            WHERE usuario_email='$email'");
            if($check_email->rowCount()>0){
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El Email ingresado ya se encuentra registrado
                </div> 
        ';
        exit();
            }
            $check_email=null;
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El Email ingresado no es valido
                </div> 
        ';
        exit();
        }
    }

    # Verificando usuario #
    if($usuario!=$datos['usuario_usuario']){
        $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM 
    usuario WHERE usuario_usuario='$usuario'");
    if($check_usuario->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Usuario ingresado ya se encuentra registrado
            </div> 
    ';
    exit();
    }
    $check_usuario=null;

    }

    # Verificando claves #
    if($clave_1!="" || $clave_2!=""){
        if(vericar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || vericar_datos ("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las claves no coinciden con el formato solicitado
            </div> 
        ';
        exit();
    }else{
        if($clave_1!=$clave_2){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La clave ingresada no coincide
                </div> 
        ';
        exit();
        }else{
            $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
        }

    }
    }else{
        $clave=$datos['usuario_clave'];

    }

    # Actualizar datos #
    $actualizar_usuario=conexion();
    $actualizar_usuario=$actualizar_usuario->prepare("UPDATE usuario SET 
    usuario_nombre=:nombre,usuario_apellido=:apellido,usuario_usuario=:usuario,
    usuario_clave=:clave,usuario_email=:email WHERE usuario_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email,
        ":id"=>$id
    ];

    if($actualizar_usuario->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Usuario actualizado!</strong><br>
                Usuario se actualizo correctamente
            </div> 
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Usuario no se pudo actualizar
            </div> 
        ';
    }
    $actualizar_usuario=null;

  
    