<?php
// Fragmento: tarjetas de propiedades filtradas
?>
<?php if (empty($properties)) { ?>
    <div class="col-12 text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4>No se encontraron propiedades</h4>
        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
    </div>
<?php } else {
    foreach ($properties as $prop) {
        $isFavorite = in_array($prop['id'], $userFavorites ?? []);
        ?>
        <div class="col-lg-4 col-md-6 mb-4 property-item" data-prop-id="<?= $prop['id'] ?>">
            <div class="card property-card h-100 position-relative">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($prop['imagen_principal']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($prop['titulo']) ?>"
                         onerror="this.src='assets/images/placeholder.jpg'">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($prop['tipo'])) ?></span>
                    </div>
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-success"><?= ucfirst($prop['estado'] ?? 'activa') ?></span>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-2 mt-2 mb-2 me-3">
                    <button class="favorite-btn p-0 border-0 bg-transparent<?= $isFavorite ? ' active' : '' ?>" 
                            data-id="<?= $prop['id'] ?>" 
                            style="z-index:2;">
                        <i class="fa-heart <?= $isFavorite ? 'fas text-danger' : 'far' ?>" 
                           id="favoriteIcon-<?= $prop['id'] ?>" 
                           style="font-size: 2.2rem; color: #dc3545;"></i>
                    </button>
                    <span class="small text-muted" id="favoriteCount-<?= $prop['id'] ?>">
                        <?= $prop['favorites_count'] ?? 0 ?>
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($prop['titulo']) ?></h5>
                    <p class="property-price mb-2"><?= $prop['precio_formateado'] ?></p>
                    <p class="property-location mb-3">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        <?= htmlspecialchars($prop['ubicacion'] ?? 'Ubicación no especificada') ?>
                    </p>
                    <div class="row text-center mb-3 align-items-end" style="min-height: 70px;">
                        <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                            <div>
                                <i class="fas fa-bed fa-lg mb-1"></i>
                                <span class="fw-bold ms-1"><?= $prop['habitaciones'] ?></span>
                            </div>
                            <small class="text-muted">Habitaciones</small>
                        </div>
                        <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                            <div>
                                <i class="fas fa-bath fa-lg mb-1"></i>
                                <span class="fw-bold ms-1"><?= $prop['banos'] ?? 'N/A' ?></span>
                            </div>
                            <small class="text-muted">Baños</small>
                        </div>
                        <div class="col-4 d-flex flex-column justify-content-end align-items-center">
                            <div>
                                <i class="fas fa-ruler-combined fa-lg mb-1"></i>
                                <span class="fw-bold ms-1"><?= $prop['area'] ?>m²</span>
                            </div>
                            <small class="text-muted">Área</small>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="/properties/detail/<?= $prop['id'] ?>" class="btn btn-outline-primary">Ver Detalles</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?> 