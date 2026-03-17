<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Nuevo cliente'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm p-4" style="max-width:700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-danger"></i>Nuevo cliente</h6>
        <a href="/admin/clientes" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form method="POST" action="/admin/clientes">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nombre" placeholder="Ej: Carlos" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="apellido" placeholder="Ej: Gómez" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Documento de identidad <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="documento_identidad" placeholder="Ej: 1023456789" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="tel" class="form-control" name="telefono" placeholder="Ej: 3001234567">
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="correo@ejemplo.com">
            </div>
            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" placeholder="Calle, ciudad">
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                <button type="submit" class="btn btn-danger">Guardar cliente</button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection(); ?>
