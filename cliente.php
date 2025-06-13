<?php
include_once './conexion/cone.php';

if (!$con)
    die("Error de Conexión:" . mysqli_connect_error());

$sql = "select * from tb_cliente";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error de Conexión:" . mysqli_connect_error());
}


?>


<!DOCTYPE html>
<html lang="es">

<?php
include_once './includes/head.php'
?>

<body>

    <?php include_once './includes/header.php' ?>
    <br>
    <div class="content">
        <div class="container-fluid text-center mt-6">
            <div class="d-flex justify-content-between align-items-center" style="padding-top: 30px; ;">
                <h3 class="mb-0">Listado de Cliente</h3>
                <a href="cliente_add.php" class="btn btn-success">Agregar</a>

            </div>
            <br>
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <th style="text-align: center;">Código</th>
                    <th style="text-align: center;">Nombres</th>
                    <th style="text-align: center;">Apellidos</th>
                    <th style="text-align: center;">Dirección</th>
                    <th style="text-align: center;">Telefono</th>
                    <th style="text-align: center;">Código</th>
                    <th style="text-align: center;">Fecha Registro</th>
                    <th style="text-align: center;">Opciones</th>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {


                    ?>
                        <tr>
                            <td><?php echo $row["client_id"] ?></td>
                            <td><?php echo $row["client_nombres"] ?></td>
                            <td><?php echo $row["client_apellidos"] ?></td>
                            <td><?php echo $row["client_direccion"] ?></td>
                            <td><?php echo $row["client_telefono"] ?></td>
                            <td><?php echo $row["client_correo"] ?></td>
                            <td><?php echo $row["fecha_registro"] ?></td>
                            <td>
                                <a href="cliente_actu.php?id=<?php echo $row['client_id']; ?>" class="btn btn-success">
                                    <i class="fa fa-pencil"></i> Editar
                                </a>

                                <a class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php include_once './includes/footer.php' ?>

</body>

</html>