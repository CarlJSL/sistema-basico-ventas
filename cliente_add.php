<?php
include_once './conexion/cone.php';
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
                <h3 class="mb-0">Cliente: Agregar Datos</h3>
            </div>
            <br>

            <?php
            $nombre = isset($_POST['txtNombre']) ? trim($_POST['txtNombre']) : '';
            $apellido = isset($_POST['txtApe']) ? trim($_POST['txtApe']) : '';
            $direccion = isset($_POST['txtDirec']) ? trim($_POST['txtDirec']) : '';
            $email = isset($_POST['txtEmail']) ? trim($_POST['txtEmail']) : '';
            $telefono = isset($_POST['txtTelf']) ? trim($_POST['txtTelf']) : '';
            $fechaRegistro = isset($_POST['txtFecha']) ? trim($_POST['txtFecha']) : '';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!empty($nombre) && !empty($apellido) && !empty($direccion) && !empty($email) && !empty($telefono) && !empty($fechaRegistro)) {
                    $sql =  "insert into tb_cliente (client_nombres, client_apellidos, client_direccion, client_correo,client_telefono, fecha_registro) 
                    values (?,?,?,?,?,?)";

                    if ($stm = $con->prepare($sql)) {
                        $stm->bind_param('ssssss', $nombre, $apellido, $direccion, $email, $telefono, $fechaRegistro);

                        if ($stm->execute()) {
                            echo "<div class='alert alert-success d-flex align-items-center' role='alert'><div>Se Guardaron los Datos Correctamente</div></div>";
                        } else {
                            echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dissmis='alert' aria-hidden='true'>&times</button>No se Guardaron los Datos</div>";
                        }
                        $stm->close();
                    } else {
                        echo "Error al preparar la consulta" . htmlspecialchars($con->error);
                    }
                } else {
                    echo "<div class='alert alert-warning'>Por favor completar todos los campos</div>";
                }
            }
            ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="txtNombre">Nombres</label>
                    <input type="text" name="txtNombre" id="txtNombre" class="form-control" placeholder="Ingrese su Nombre" required>
                </div>
                <div class="form-group">
                    <label for="txtApe">Apellidos</label>
                    <input type="text" name="txtApe" id="txtApe" class="form-control" placeholder="Ingrese su Apellido" required>
                </div>
                <div class="form-group">
                    <label for="txtDirec">Dirección</label>
                    <input type="text" name="txtDirec" id="txtDirec" class="form-control" placeholder="Ingrese su Direccion" required>
                </div>
                <div class="form-group">
                    <label for="txtEmail">Correo Electrónico</label>
                    <input type="text" name="txtEmail" id="txtEmail" class="form-control" placeholder="Ingrese su Correo Electrónico" required>
                </div>
                <div class="form-group">
                    <label for="txtTelf">Teléfono</label>
                    <input type="text" name="txtTelf" id="txtTelf" class="form-control" placeholder="Ingrese su Número de Celular" required>
                </div>
                <div class="form-group">
                    <label for="txtFecha">Fecha de Registro</label>
                    <input type="text" name="txtFecha" id="txtFecha" class="form-control" placeholder="Ingrese la Fecha de Registro" required>
                </div>
                <br>

                <div class="form-buttons">
                    <button type="submit" name="add" class="btn btn-primary"><i class="fa fa-floppy-o"></i>Guardar Datos</button>
                    <a href="cliente.php" class="btn btn-danger"><i class="fa fa-ban"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <?php include_once './includes/footer.php' ?>

</body>

</html>