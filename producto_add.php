<?php
include_once './conexion/cone.php';

$nombre = $_POST["nombre"] ?? '';
$descripcion = $_POST["descripcion"] ?? '';
$modelo = $_POST["modelo"] ?? '';
$marca = $_POST["marca"] ?? '';
$idcategoria = intval($_POST["categoria"] ?? 0);
$idsubcategoria = intval($_POST["subcategoria"] ?? 0);
$precio = floatval($_POST["precio"] ?? 0);
$stock = intval($_POST["stock"] ?? 0);

// Obtener categorías y subcategorías
$sqlCat = "SELECT * FROM categoria";
$categorias = mysqli_query($con, $sqlCat);

$sqlSub = "SELECT * FROM subcategoria";
$subcategorias = mysqli_query($con, $sqlSub);

// Procesar envío
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($nombre && $descripcion && $modelo && $marca && $idcategoria && $idsubcategoria && $precio > 0 && $stock > 0) {

        // Validar duplicado: mismo nombre y modelo
        $sql_check = "SELECT prod_codi FROM producto WHERE prod_nombre = ? AND prod_model = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("ss", $nombre, $modelo);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<script>
                alert('Ya existe un producto con el mismo nombre y modelo.');
                window.location.href = 'producto_listado.php';
            </script>";
            exit();
        }
        $stmt_check->close();

        // Insertar producto
        $sql = "INSERT INTO producto (
            prod_nombre, prod_descripcion, prod_model, prod_marca,
            idsubcategoria, proc_precio, prod_stock
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssidi", $nombre, $descripcion, $modelo, $marca, $idsubcategoria, $precio, $stock);


        // Suponiendo que ya estás dentro de un archivo PHP
        $alert = "";
        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                Producto registrado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="window.location.href=\'producto.php\'"></button>
              </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Error al registrar producto: ' . $stmt->error . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="window.location.href=\'producto.php\'"></button>
              </div>';
        }
        $stmt->close();
    } else {
        $alert = '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                Por favor completa todos los campos correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="window.location.href=\'producto.php\'"></button>
              </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php' ?>

<body>
    <?php include_once './includes/header.php' ?>

    <div class="content">
        <div class="container mt-5">
        <h3>Agregar Nuevo Producto</h3>
        <div class="container mt-4">
            <?php if (!empty($alert)) echo $alert; ?>
        </div>
        <form action="producto_add.php" method="POST">

            <div class="form-group mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" required minlength="3" maxlength="100">
            </div>

            <div class="form-group mb-3">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" class="form-control" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" class="form-control" required maxlength="50">
            </div>

            <div class="form-group mb-3">
                <label for="marca">Marca</label>
                <input type="text" name="marca" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria" class="form-select" required>
                    <option value="">-- Selecciona Categoría --</option>
                    <?php while ($cat = mysqli_fetch_assoc($categorias)) { ?>
                        <option value="<?php echo $cat['idcategoria']; ?>">
                            <?php echo $cat['nombre_categoria']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="subcategoria">Subcategoría</label>
                <select name="subcategoria" id="subcategoria" class="form-select" required>
                    <option value="">-- Selecciona Subcategoría --</option>
                    <?php mysqli_data_seek($subcategorias, 0);
                    while ($sub = mysqli_fetch_assoc($subcategorias)) { ?>
                        <option value="<?php echo $sub['idsubcategoria']; ?>" data-cat="<?php echo $sub['idcategoria']; ?>">
                            <?php echo $sub['nombre_subcategoria']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="precio">Precio</label>
                <input type="number" name="precio" step="0.01" min="0.01" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="stock">Stock</label>
                <input type="number" name="stock" min="1" class="form-control" required>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-floppy-o"></i> Guardar Datos
                </button>
                <a href="producto.php" class="btn btn-danger">
                    <i class="fa fa-ban"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaSelect = document.getElementById('categoria');
            const subcategoriaSelect = document.getElementById('subcategoria');
            const allOptions = Array.from(subcategoriaSelect.options);

            categoriaSelect.addEventListener('change', function() {
                const catId = this.value;
                subcategoriaSelect.innerHTML = '<option value="">-- Selecciona Subcategoría --</option>';

                allOptions.forEach(opt => {
                    if (opt.dataset.cat === catId) {
                        subcategoriaSelect.appendChild(opt.cloneNode(true));
                    }
                });
            });
        });
    </script>

    <?php include_once './includes/footer.php'; ?>
</body>

</html>