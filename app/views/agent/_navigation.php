<?php
// Detectar la página actual para marcar como activa
$current_page = $_SERVER['REQUEST_URI'];
$current_page = str_replace('/propeasy/public', '', $current_page); // Normalizar URL

function isActivePage($page) {
    global $current_page;
    return strpos($current_page, $page) !== false ? 'active' : '';
}
?>

<!-- CSS específico para navegación de agentes -->
<link rel="stylesheet" href="/assets/css/agent-navigation.css">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">
            <i class="fas fa-building me-2"></i>PropEasy
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('/agent/dashboard') ?: isActivePage('/dashboard/agent') ?>" href="/agent/dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('/agent/properties') ?>" href="/agent/properties">
                        <i class="fas fa-home me-1"></i>Mis Propiedades
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('/agent/appointments') ?>" href="/agent/appointments">
                        <i class="fas fa-calendar me-1"></i>Citas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('/agent/messages') ?>" href="/agent/messages">
                        <i class="fas fa-envelope me-1"></i>Mensajes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('/agent/clients') ?>" href="/agent/clients">
                        <i class="fas fa-users me-1"></i>Clientes
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-tools me-1"></i>Herramientas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/properties/publish">
                            <i class="fas fa-plus me-2"></i>Nueva Propiedad
                        </a></li>
                        <li><a class="dropdown-item" href="/properties">
                            <i class="fas fa-search me-2"></i>Buscar Propiedades
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/agent/stats">
                            <i class="fas fa-chart-bar me-2"></i>Estadísticas
                        </a></li>
                        <li><a class="dropdown-item" href="/solicitudes/mis_solicitudes">
                            <i class="fas fa-clipboard-list me-2"></i>Mis Solicitudes
                        </a></li>
                    </ul>
                </li>
            </ul>
            
            <div class="d-flex align-items-center">
                <!-- Notificaciones -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-light position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            <?= isset($stats['unread_contacts']) ? $stats['unread_contacts'] : '0' ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li><a class="dropdown-item" href="/agent/messages">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <?= isset($stats['unread_contacts']) ? $stats['unread_contacts'] : '0' ?> mensajes nuevos
                        </a></li>
                        <li><a class="dropdown-item" href="/agent/appointments">
                            <i class="fas fa-calendar me-2 text-warning"></i>
                            <?= isset($stats['pending_appointments']) ? $stats['pending_appointments'] : '0' ?> citas pendientes
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="/agent/notifications">
                            Ver todas
                        </a></li>
                    </ul>
                </div>

                <!-- Usuario -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Agente' ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Mi Cuenta</h6></li>
                        <li><a class="dropdown-item" href="/agent/profile">
                            <i class="fas fa-user me-2"></i>Mi Perfil
                        </a></li>
                        <li><a class="dropdown-item" href="/agent/settings">
                            <i class="fas fa-cog me-2"></i>Configuración
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Acciones Rápidas</h6></li>
                        <li><a class="dropdown-item" href="/properties/publish">
                            <i class="fas fa-plus me-2"></i>Nueva Propiedad
                        </a></li>
                        <li><a class="dropdown-item" href="/agent/appointments">
                            <i class="fas fa-calendar-plus me-2"></i>Nueva Cita
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/auth/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav> 