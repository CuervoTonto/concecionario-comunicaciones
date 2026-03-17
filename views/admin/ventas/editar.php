<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Editar venta'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm p-4" style="max-width:700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-danger"></i>Editar venta #<?= $venta->venta_id ?></h6>
        <a href="/admin/ventas" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form method="POST" action="/admin/ventas/<?= $venta->venta_id ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">ID Cliente <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="cliente_id"
                       value="<?= $venta->cliente_id ?>" min="1" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">ID Vehículo <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="vehiculo_id"
                       value="<?= $venta->vehiculo_id ?>" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de venta <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="fecha_venta"
                       value="<?= $venta->fecha_venta ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio final <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="precio_final"
                           min="0" step="1000" value="<?= $venta->precio_final ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Método de pago</label>
                <select class="form-select" name="metodo_pago">
                    <option value="">Seleccionar...</option>
                    <?php foreach (['Contado','Crédito','Leasing','Transferencia'] as $mp): ?>
                    <option value="<?= $mp ?>" <?= $venta->metodo_pago === $mp ? 'selected' : '' ?>>
                        <?= $mp ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="/admin/ventas" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-danger">Guardar cambios</button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection(); ?>
