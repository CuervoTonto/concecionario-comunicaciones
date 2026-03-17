<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Editar cliente'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm p-4" style="max-width:700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-danger"></i>Editar cliente #<?= $cliente->cliente_id ?></h6>
        <a href="/admin/clientes" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form method="POST" action="/admin/clientes/<?= $cliente->cliente_id ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nombre"
                       value="<?= htmlspecialchars($cliente->nombre) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="apellido"
                       value="<?= htmlspecialchars($cliente->apellido) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Documento de identidad <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="documento_identidad"
                       value="<?= htmlspecialchars($cliente->documento_identidad) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="tel" class="form-control" name="telefono"
                       value="<?= htmlspecialchars($cliente->telefono ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email"
                       value="<?= htmlspecialchars($cliente->email ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion"
                       value="<?= htmlspecialchars($cliente->direccion ?? '') ?>">
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="/admin/clientes" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-danger">Guardar cambios</button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection(); ?>
