<?php
    require_once "main.php";

    $id=limpiar_cadena($_POST['categoria_id']);

    # Verificando categoria #

    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE 
    categoria_id='$id'");

    if($check_categoria->rowCount()<0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no se encuentra en la busqueda
            </div> 
        ';
        exit();
    }else{
        $datos=$check_categoria->fetch();
    }
    $check_categoria=null;

    # Almacenamiento de datos #
    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

    # Verificacion de campos obligatorios #
    if($nombre==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se han llenado todos los campos que son obligatorios
            </div> 
        ';
        exit();
    }

    # Verificacion de Integridad de los datos #
    if(vericar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El nombre no coincide con el formato solicitado
            </div> 
        ';
        exit();
    }

    if($ubicacion!=""){
        if(vericar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La ubicacion no coincide con el formato solicitado
                </div> 
            ';
            exit();
        }
    }

    # Verificando nombre #
    if($nombre!=$datos['categoria_nombre']){
        $check_nombre=conexion();
        $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria 
        WHERE categoria_nombre='$nombre'");
        if($check_nombre->rowCount()>0){
           echo '
               <div class="notification is-danger is-light">
                   <strong>¡Ocurrio un error inesperado!</strong><br>
                   El nombre ingresado ya se encuentra registrado
               </div> 
          ';
          exit();
        }
        $check_nombre=null;
    }

    # Actualizar datos #
    $actualizar_categoria=conexion();
    $actualizar_categoria=$actualizar_categoria->prepare("UPDATE categoria SET 
    categoria_nombre=:nombre,categoria_ubicacion=:ubicacion WHERE categoria_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion,
        ":id"=>$id
    ];

    if($actualizar_categoria->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Categoria actualizada!</strong><br>
                Categoria se actualizo correctamente
            </div> 
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Categoria no se pudo actualizar
            </div> 
        ';
    }
    $actualizar_categoria=null;
    