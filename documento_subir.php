<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">
            <h2>Subir documento</h2>
        </div>

        <div class="card-body">

            <form
                action="documento_controlador.php?accion=guardar"
                method="POST"
                enctype="multipart/form-data"
                novalidate
                onsubmit="return validarDocumento(true)">

                <div id="errores" class="alert alert-danger d-none"></div>

                <!-- TITULO -->
                <div class="mb-3">
                    <label class="form-label">Titulo</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" maxlength="45" required>
                </div>

                <!-- ARCHIVO -->
                <div class="mb-3">
                    <label class="form-label">Archivo</label>
                    <input type="file" id="archivo" name="archivo" class="form-control" required>
                    <div class="form-text">
                        Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, PPT y PPTX.
                    </div>
                </div>

                <!-- CATEGORIAS -->
                <div class="mb-3">
                    <label class="form-label">Categorias</label>

                    <select id="categorias" name="categorias[]" class="form-select" multiple size="5" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo (int) $categoria['Id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['Nombre_categoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-text">
                        Debes seleccionar al menos una categoria.
                        Podes seleccionar varias categorias manteniendo presionado Ctrl.
                    </div>
                </div>

                <!-- BOTONES -->
                <button type="submit" class="btn btn-primary">Subir documento</button>

                <a href="documento_controlador.php?accion=listar" class="btn btn-secondary">Cancelar</a>

            </form>

        </div>

    </div>

</div>

<?php require __DIR__ . "/validaciones_js.php"; ?>

</body>
</html>
