<?php $this->layout(fromViews('layouts/app.php')); ?>

<?php $this->section('title', 'AutoElite | Concesionario'); ?>

<?php $this->section('styles'); ?>
.hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    min-height: 90vh;
}
.car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.car-card { transition: transform 0.3s ease; }
.badge-km { background-color: #0f3460; }
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>

<!-- Hero -->
<section class="hero d-flex align-items-center text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <p class="text-uppercase text-danger fw-semibold mb-2">Bienvenido a AutoElite</p>
                <h1 class="display-4 fw-bold mb-4">Tu próximo auto te está esperando</h1>
                <p class="lead text-white-50 mb-4">Más de 200 vehículos nuevos y seminuevos. Financiamiento flexible, garantía extendida y la mejor atención del mercado.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#catalogo" class="btn btn-primary btn-lg px-4">Ver catálogo</a>
                    <a href="#contacto" class="btn btn-outline-light btn-lg px-4">Contáctanos</a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <i class="bi bi-car-front-fill" style="font-size: 14rem; opacity: 0.15;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-dark text-white py-4">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-danger mb-0">+<?= number_format($totalVehiculos, 0, ',', '.') ?></h3>
                <small class="text-white-50">Vehículos disponibles</small>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-danger mb-0">15+</h3>
                <small class="text-white-50">Años de experiencia</small>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-danger mb-0">+<?= number_format($totalClientes, 0, ',', '.') ?></h3>
                <small class="text-white-50">Clientes satisfechos</small>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-danger mb-0">+<?= number_format($totalMarcas, 0, ',', '.') ?></h3>
                <small class="text-white-50">Marcas disponibles</small>
            </div>
        </div>
    </div>
</section>

<!-- Catálogo -->
<section id="catalogo" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold section-title mb-5">Vehículos destacados</h2>
        <div class="row g-4">
            <?php foreach ($vehiculos as $v): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card car-card h-100 shadow-sm border-0">
                    <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height:180px;">
                        <i class="bi bi-car-front text-secondary" style="font-size:5rem;"></i>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title mb-0 fw-bold"><?= htmlspecialchars($v->modelo) ?> <?= $v->anio ?></h5>
                                <small class="text-muted"><?= htmlspecialchars($v->marca) ?></small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <?php if ($v->color): ?>
                            <span class="badge bg-light text-dark border"><i class="bi bi-palette me-1"></i><?= htmlspecialchars($v->color) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-danger">$<?= number_format($v->precio, 0, ',', '.') ?></span>
                            <a href="#contacto" class="btn btn-outline-primary btn-sm">Ver más</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($vehiculos)): ?>
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-car-front fs-1 opacity-25 d-block mb-2"></i>
                Próximamente nuevos vehículos disponibles.
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Servicios -->
<section id="servicios" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center fw-bold section-title mb-5">Nuestros servicios</h2>
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-credit-card-2-front text-danger fs-1 mb-3 d-block"></i>
                    <h5 class="fw-bold">Financiamiento</h5>
                    <p class="text-muted small">Planes a tu medida con las mejores tasas del mercado.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-shield-check text-danger fs-1 mb-3 d-block"></i>
                    <h5 class="fw-bold">Garantía extendida</h5>
                    <p class="text-muted small">Protege tu inversión con nuestra garantía de hasta 3 años.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-tools text-danger fs-1 mb-3 d-block"></i>
                    <h5 class="fw-bold">Taller certificado</h5>
                    <p class="text-muted small">Servicio y mantenimiento con técnicos especializados.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-arrow-left-right text-danger fs-1 mb-3 d-block"></i>
                    <h5 class="fw-bold">Permuta</h5>
                    <p class="text-muted small">Entrega tu auto actual como parte de pago.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contacto -->
<section id="contacto" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold section-title mb-5">Contáctanos</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4">Solicita información</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" placeholder="Tu nombre">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" placeholder="3001234567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vehículo de interés</label>
                            <select class="form-select" name="vehiculo">
                                <option value="">Selecciona un modelo</option>
                                <?php foreach ($vehiculos as $v): ?>
                                <option value="<?= $v->vehiculo_id ?>">
                                    <?= htmlspecialchars($v->marca . ' ' . $v->modelo . ' ' . $v->anio) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Mensaje</label>
                            <textarea class="form-control" rows="3" placeholder="¿En qué podemos ayudarte?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar solicitud</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h5 class="fw-bold mb-4">Información de contacto</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="d-flex gap-3 align-items-start">
                            <i class="bi bi-geo-alt-fill text-danger fs-5 mt-1"></i>
                            <span class="text-muted">Cra. 7 #71-21, Bogotá, Colombia</span>
                        </li>
                        <li class="d-flex gap-3 align-items-center">
                            <i class="bi bi-telephone-fill text-danger fs-5"></i>
                            <span class="text-muted">601 234 5678</span>
                        </li>
                        <li class="d-flex gap-3 align-items-center">
                            <i class="bi bi-envelope-fill text-danger fs-5"></i>
                            <span class="text-muted">ventas@autoelite.co</span>
                        </li>
                        <li class="d-flex gap-3 align-items-start">
                            <i class="bi bi-clock-fill text-danger fs-5 mt-1"></i>
                            <span class="text-muted">Lun – Sáb: 9:00 – 19:00<br>Dom: 10:00 – 15:00</span>
                        </li>
                    </ul>
                    <hr>
                    <div class="d-flex gap-3 mt-2">
                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->endSection(); ?>
