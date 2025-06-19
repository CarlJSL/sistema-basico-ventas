<?php
include './conexion/cone.php';
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>

    <div class="content">
        <div class="container mt-5">
        <h2 class="mb-4">Categorías y Subcategorías</h2>
        <div class="d-flex justify-content-end mb-3 gap-2">
            <a href="cat_agregar.php" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 35%;">Categoría</th>
                    <th style="width: 35%;">Subcategoría</th>
                    <th style="width: 30%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $categorias = mysqli_query($con, "SELECT * FROM categoria ORDER BY idcategoria ASC");
                while ($cat = mysqli_fetch_assoc($categorias)) {
                    $idcat = $cat['idcategoria'];
                    $nombre_cat = $cat['nombre_categoria'];
                    $subcategorias = mysqli_query($con, "SELECT * FROM subcategoria WHERE idcategoria = $idcat");

                    $rowspan = mysqli_num_rows($subcategorias) ?: 1;
                    $primeraFila = true;

                    if ($rowspan === 1 && mysqli_num_rows($subcategorias) === 0) {
                        // Categoría sin subcategorías
                        echo "<tr>
                                <td>{$nombre_cat}</td>
                                <td class='text-muted'><i>Sin subcategorías</i></td>
                                <td>
                                    <a href='cat_edit.php?id={$idcat}&tipo=categoria' class='btn btn-warning btn-sm'>Editar Categoría</a>
                                </td>
                              </tr>";
                    } else {
                        while ($sub = mysqli_fetch_assoc($subcategorias)) {
                            echo "<tr>";
                            if ($primeraFila) {
                                echo "<td rowspan='{$rowspan}'>{$nombre_cat}</td>";
                                $primeraFila = false;
                            }
                            echo "
                                <td>{$sub['nombre_subcategoria']}</td>
                                <td>
                                    <a href='cat_edit.php?id={$idcat}&tipo=categoria' class='btn btn-warning btn-sm'>Editar Categoría</a>
                                    <a href='cat_edit.php?id={$sub['idsubcategoria']}&tipo=subcategoria' class='btn btn-info btn-sm ms-1'>Editar Subcategoría</a>
                                </td>
                            </tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
    <?php include_once './includes/footer.php'; ?>
</body>
</html>
