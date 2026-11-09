<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">
            <h2>Editar documento</h2>
        </div>

        <div class="card-body">

            <form
                action="documento_controlador.php?accion=actualizar"
                method="POST"
                enctype="multipart/form-data"
                novalidate
                onsubmit="return validarDocumento(false)">

                <div id="errores" class="alert alert-danger d-none"></div>

                <!-- ID -->
                <input type="hidden" name="id_documento" value="<?php echo (int) $documento['Id_documento']; ?>">

                <!-- TITULO -->
                <div class="mb-3">
                    <label class="form-label">Titulo</label>
                    <input
                        type="text"
                        id="titulo" name="titulo"
                        class="form-control"
                        maxlength="45"
                        value="<?php echo htmlspecialchars($documento['Titulo']); ?>"
                        required>
                </div>

                <!-- ARCHIVO ACTUAL -->
                <div class="mb-3">
                    <label class="form-label">Archivo actual</label>
                    <div>
                        <a
                            href="../<?php echo htmlspecialchars($documento['Archivo']); ?>"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            Ver archivo actual
                        </a>
                    </div>
                </div>

                <!-- NUEVO ARCHIVO -->
                <div class="mb-3">
                    <label class="form-label">Reemplazar archivo</label>
                    <input type="file" id="archivo" name="archivo" class="form-control">
                    <div class="form-text">
                        Deja este campo vacio si queres mantener el archivo actual.
                        Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, PPT y PPTX.
                    </div>
                </div>

                <!-- CATEGORIAS -->
                <div class="mb-3">
                    <label class="form-label">Categorias</label>

                    <select id="categorias" name="categorias[]" class="form-select" multiple size="5" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option
                                value="<?php echo (int) $categoria['Id_categoria']; ?>"
                                <?php if (in_array($categoria['Id_categoria'], $seleccionadas)) { echo "selected"; } ?>>
                                <?php echo htmlspecialchars($categoria['Nombre_categoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-text">
                        Podes seleccionar varias categorias manteniendo presionado Ctrl.
                    </div>
                </div>

                <!-- ESTADO -->
                <div class="mb-3">
                    <label class="form-label">Estado actual</label>

                    <?php if ($documento['Activo'] == 1): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Baja logica</span>
                    <?php endif; ?>
                </div>

                <!-- BOTONES -->
                <button type="submit" class="btn btn-primary">Guardar cambios</button>

                <a href="documento_controlador.php?accion=listar" class="btn btn-secondary">Cancelar</a>

            </form>

        </div>

    </div>

</div>

<?php require __DIR__ . "/validaciones_js.php"; ?>

</body>
</html>
