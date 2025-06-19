<?php
include_once './conexion/cone.php';
$sql = "SELECT p.*, s.nombre_subcategoria, c.nombre_categoria 
        FROM producto p
        JOIN subcategoria s ON p.idsubcategoria = s.idsubcategoria
        JOIN categoria c ON s.idcategoria = c.idcategoria";
$res = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>
    <div class="content">
        <div class="container-fluid mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Listado de Productos</h3>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
            </div>

            <?php if (isset($_GET['msg'])) : ?>
                <div class="alert alert-success">
                    <?php
                    echo match ($_GET['msg']) {
                        'agregado' => 'Producto agregado correctamente.',
                        'editado' => 'Producto actualizado.',
                        'eliminado' => 'Producto eliminado.',
                        default => 'Acción completada.'
                    };
                    ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Detalle</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = $res->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $p['prod_codi'] ?></td>
                            <td><?= $p['prod_nombre'] ?></td>
                            <td><?= $p['prod_descripcion'] ?></td>
                            <td><?= $p['prod_model'] ?></td>
                            <td><?= $p['prod_marca'] ?></td>
                            <td><?= $p['nombre_categoria'] ?></td>
                            <td><?= $p['nombre_subcategoria'] ?></td>
                            <td><?= $p['proc_precio'] ?></td>
                            <td><?= $p['prod_stock'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" data-id="<?= $p['prod_codi'] ?>" data-nombre="<?= $p['prod_nombre'] ?>" data-detalle="<?= $p['prod_descripcion'] ?>" data-modelo="<?= $p['prod_model'] ?>" data-marca="<?= $p['prod_marca'] ?>" data-sub="<?= $p['idsubcategoria'] ?>" data-precio="<?= $p['proc_precio'] ?>" data-stock="<?= $p['prod_stock'] ?>">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <a href="producto_eliminar.php?id=<?= $p['prod_codi'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Agregar -->
        <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="producto_add.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
                        <input name="descripcion" class="form-control mb-2" placeholder="Descripción" required>
                        <input name="modelo" class="form-control mb-2" placeholder="Modelo" required>
                        <input name="marca" class="form-control mb-2" placeholder="Marca" required>
                        <select name="subcategoria" class="form-select mb-2" required>
                            <option value="">Subcategoría</option>
                            <?php
                            $sub = $con->query("SELECT idsubcategoria, nombre_subcategoria FROM subcategoria");
                            while ($s = $sub->fetch_assoc()) :
                            ?>
                                <option value="<?= $s['idsubcategoria'] ?>"><?= $s['nombre_subcategoria'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <input name="precio" type="number" step="0.01" class="form-control mb-2" placeholder="Precio" required>
                        <input name="stock" type="number" class="form-control mb-2" placeholder="Stock" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Guardar</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="producto_edit.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Producto</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <input name="nombre" id="editNombre" class="form-control mb-2" required>
                        <input name="descripcion" id="editDetalle" class="form-control mb-2" required>
                        <input name="modelo" id="editModelo" class="form-control mb-2" required>
                        <input name="marca" id="editMarca" class="form-control mb-2" required>
                        <select name="subcategoria" id="editSub" class="form-select mb-2" required>
                            <?php
                            $sub2 = $con->query("SELECT idsubcategoria, nombre_subcategoria FROM subcategoria");
                            while ($s = $sub2->fetch_assoc()) :
                            ?>
                                <option value="<?= $s['idsubcategoria'] ?>"><?= $s['nombre_subcategoria'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <input name="precio" type="number" step="0.01" class="form-control mb-2" id="editPrecio" required>
                        <input name="stock" type="number" class="form-control mb-2" id="editStock" required>
                    </div>
                    <div class="modal-footer">
                        <button