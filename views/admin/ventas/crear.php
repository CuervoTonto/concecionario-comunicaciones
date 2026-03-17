<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Registrar venta'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm p-4" style="max-width:700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-danger"></i>Registrar venta</h6>
        <a href="/admin/ventas" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form method="POST" action="/admin/ventas">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">ID Cliente <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="cliente_id" placeholder="Ej: 1" min="1" required>
                <div class="form-text">Ingresa el ID del cliente registrado.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">ID Vehículo <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="vehiculo_id" placeholder="Ej: 5" min="1" required>
                <div class="form-text">Ingresa el ID del vehículo registrado.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de venta <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="fecha_venta" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio final <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="precio_final" min="0" step="1000" placeholder="85000000" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Método de pago</label>
                <select class="form-select" name="metodo_pago">
                    <option value="">Seleccionar...</option>
                    <option value="Contado">Contado</option>
                    <option value="Crédito">Crédito</option>
                    <option value="Leasing">Leasing</option>
                    <option value="Transferencia">Transferencia bancaria</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                <button type="submit" class="btn btn-danger">Registrar venta</button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection(); ?>
