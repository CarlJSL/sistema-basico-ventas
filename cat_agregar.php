<?php
include_once './conexion/cone.php';
$alert = "";

// Procesamiento al enviar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo = $_POST['tipo'] ?? '';
    $nombre_categoria = trim($_POST['categoria_nombre'] ?? ''); // Para agregar categoría
    $nueva_categoria = trim($_POST['nueva_categoria_nombre'] ?? ''); // Para subcategoría
    $nombre_subcategoria = trim($_POST['nombre_subcategoria'] ?? '');
    $idcategoria = intval($_POST['idcategoria'] ?? 0);

    if ($tipo === 'categoria' && $nombre_categoria !== '') {
        // Insertar categoría
        $stmt = $con->prepare("INSERT INTO categoria (nombre_categoria) VALUES (?)");
        $stmt->bind_param("s", $nombre_categoria);
        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Categoría agregada correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="window.location.href=\'opciones-prod.php\'"></button>
                      </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al agregar categoría.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="window.location.href=\'opciones-prod.php\'"></button>
                      </div>';
        }
        $stmt->close();
    } elseif ($tipo === 'subcategoria' && $nombre_subcategoria !== '') {
        if ($idcategoria === 0 && $nueva_categoria !== '') {
            // Crear nueva categoría para la subcategoría
            $stmt = $con->prepare("INSERT INTO categoria (nombre_categoria) VALUES (?)");
            $stmt->bind_param("s", $nueva_categoria);
            if ($stmt->execute()) {
                $idcategoria = $stmt->insert_id;
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error al crear la categoría para la subcategoría.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                          </div>';
                $stmt->close();
            }
        }

        if ($idcategoria > 0) {
            // Insertar subcategoría
            $stmt = $con->prepare("INSERT INTO subcategoria (nombre_subcategoria, idcategoria) VALUES (?, ?)");
            $stmt->bind_param("si", $nombre_subcategoria, $idcategoria);
            if ($stmt->execute()) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Subcategoría agregada correctamente.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="window.location.href=\'opciones-prod.php\'"></button>
                          </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error al agregar subcategoría.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="window.location.href=\'opciones-prod.php\'"></button>
                          </div>';
            }
            $stmt->close();
        }
    } else {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Por favor, completa los campos obligatorios.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                  </div>';
    }
}

// Obtener categorías para el select
$sqlCat = "SELECT * FROM categoria ORDER BY nombre_categoria ASC";
$categorias = mysqli_query($con, $sqlCat);
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>

   <div class="container mt-5">
    <h2>Agregar Categoría o Subcategoría</h2>

    <?php if (!empty($alert)) echo $alert; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="tipo" class="form-label">¿Qué deseas agregar?</label>
            <select name="tipo" id="tipo" class="form-select" required onchange="toggleFormulario()">
                <option value="">-- Selecciona --</option>
                <option value="categoria">Categoría</option>
                <option value="subcategoria">Subcategoría</option>
            </select>
        </div>

        <!-- Formulario para Categoría -->
        <div id="formCategoria" class="mb-3" style="display: none;">
            <label class="form-label">Nombre de la Categoría</label>
            <input type="text" name="categoria_nombre" class="form-control">
        </div>

        <!-- Formulario para Subcategoría -->
        <div id="formSubcategoria" style="display: none;">
            <div class="mb-3">
                <label class="form-label">Nombre de la Subcategoría</label>
                <input type="text" name="nombre_subcategoria" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Selecciona una Categoría existente (opcional)</label>
                <select name="idcategoria" class="form-select">
                    <option value="0">-- Ninguna --</option>
                    <?php while ($cat = mysqli_fetch_assoc($categorias)) : ?>
                        <option value="<?= $cat['idcategoria'] ?>"><?= $cat['nombre_categoria'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">O escribe una nueva Categoría</label>
                <input type="text" name="nueva_categoria_nombre" class="form-control" placeholder="Si no seleccionaste una categoría arriba">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="opciones-prod.php" class="btn btn-secondary">Cancelar</a>

    </form>
</div>

<script>
    function toggleFormulario() {
        const tipo = document.getElementById("tipo").value;
        document.getElementById("formCategoria").style.display = tipo === "categoria" ? "block" : "none";
        document.getElementById("formSubcategoria").style.display = tipo === "subcategoria" ? "block" : "none";
    }
</script>

    <?php include_once './includes/footer.php'; ?>
</body>

</html>