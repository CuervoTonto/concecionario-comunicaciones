<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Nuevo vehículo'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm p-4" style="max-width:700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-danger"></i>Nuevo vehículo</h6>
        <a href="/admin/vehiculos" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form method="POST" action="/admin/vehiculos">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Marca <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="marca" placeholder="Ej: Toyota" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Modelo <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="modelo" placeholder="Ej: Corolla" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Año <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="anio" min="1990" max="2030" placeholder="<?= date('Y') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Color</label>
                <input type="text" class="form-control" name="color" placeholder="Ej: Blanco">
            </div>
            <div class="col-md-4">
                <label class="form-label">Número de serie (VIN) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="numero_serie" placeholder="Ej: 1HGBH41JXMN109186" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="precio" min="0" step="1000" placeholder="85000000" required>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                <button type="submit" class="btn btn-danger">Guardar vehículo</button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection(); ?>
