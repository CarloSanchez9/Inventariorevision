<?php
    require_once "main.php";

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
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM 
    categoria WHERE categoria_nombre='$nombre'");
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

    # Guardando los datos #
    $guardar_categoria=conexion();
    $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria
    (categoria_nombre,categoria_ubicacion) VALUES(:nombre,:ubicacion)
    ");

$marcadores=[
    ":nombre"=>$nombre,
    ":ubicacion"=>$ubicacion
];

$guardar_categoria->execute($marcadores);

if($guardar_categoria->rowCount()==1){
    echo '
            <div class="notification is-info is-light">
                <strong>¡Categoria registrada!</strong><br>
                La categoria se registro exitosamente
            </div> 
    ';
}else{
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar la categoria, por favor intente de nuevo
            </div> 
    ';

}
$guardar_categoria=null;