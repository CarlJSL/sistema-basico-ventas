
<?php
include_once './conexion/cone.php';

if (!$con)
    die("Error de Conexión:" . mysqli_connect_error());

// Consulta con JOINs para mostrar nombres reales
$sql = "
SELECT 
    p.*,
    sc.nombre_subcategoria,
    c.nombre_categoria
FROM producto p
JOIN subcategoria sc ON p.idsubcategoria = sc.idsubcategoria
JOIN categoria c ON sc.idcategoria = c.idcategoria
";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($con));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar']) && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);

    $sql = "DELETE FROM producto WHERE prod_codi = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $idEliminar);

    if ($stmt->execute()) {
        echo "<script>
            alert('Producto eliminado correctamente.');
            window.location.href = 'producto.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Error al eliminar el producto: " . $stmt->error . "');
            window.location.href = 'producto.php';
        </script>";
        exit;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php' ?>

<body>

    <?php include_once './includes/header.php' ?>
    <br>

    <div class="content">
        <div class="container-fluid text-center mt-6">
            <div class="d-flex justify-content-between align-items-center" style="padding-top: 30px;">
                <h3 class="mb-0">Listado de Productos</h3>
                <a href="producto_add.php" class="btn btn-success">Agregar</a>
            </div>
            <br>

            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Fecha Creación</th>
                        <th>Última Actualización</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td><?php echo $row["prod_codi"]; ?></td>
                            <td><?php echo $row["prod_nombre"]; ?></td>
                            <td><?php echo $row["prod_descripcion"]; ?></td>
                            <td><?php echo $row["prod_model"]; ?></td>
                            <td><?php echo $row["prod_marca"]; ?></td>
                            <td><?php echo $row["nombre_categoria"]; ?></td>
                            <td><?php echo $row["nombre_subcategoria"]; ?></td>
                            <td><?php echo $row["proc_precio"]; ?></td>
                            <td><?php echo $row["prod_stock"]; ?></td>
                            <td><?php echo $row["fecha_creacion"]; ?></td>
                            <td><?php echo $row["fecha_actualizacion"]; ?></td>
                            <td>
                                <a href="producto_edit.php?id=<?php echo $row["prod_codi"]; ?>" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                                <form method="post" action="producto.php" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');" style="display:inline;">
                                    <input type="hidden" name="eliminar_id" value="<?= $row['prod_codi'] ?>">
                                    <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>


                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php include_once './includes/footer.php' ?>
</body>

</html>