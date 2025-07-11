<?php

class SolicitudCompraController extends Controller {
    
    private $solicitudModel;
    private $propertyModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->solicitudModel = new SolicitudCompraModel();
        $this->propertyModel = new PropertyModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Mostrar formulario de solicitud de compra
     */
    public function index() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $propiedadId = $_GET['propiedad_id'] ?? null;
        
        if (!$propiedadId) {
            $this->redirect('/properties');
        }
        
        // Obtener información de la propiedad
        $propiedad = $this->propertyModel->getById($propiedadId);
        
        if (!$propiedad) {
            $this->redirect('/properties');
        }
        
        // Verificar si el usuario ya tiene una solicitud activa para esta propiedad
        $solicitudExistente = $this->solicitudModel->verificarSolicitudExistente($_SESSION['user_id'], $propiedadId);
        
        $this->view('solicitudes/formulario', [
            'propiedad' => $propiedad,
            'solicitud_existente' => $solicitudExistente
        ]);
    }
    
    /**
     * Procesar la creación de una solicitud de compra
     */
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/properties');
        }
        
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Debe iniciar sesión']);
        }
        
        // Validar datos del formulario
        $propiedadId = $_POST['propiedad_id'] ?? null;
        $mensaje = trim($_POST['mensaje'] ?? '');
        $telefonoContacto = trim($_POST['telefono_contacto'] ?? '');
        $precioOfrecido = $_POST['precio_ofrecido'] ?? null;
        $fechaVisita = $_POST['fecha_visita_preferida'] ?? null;
        $horaVisita = $_POST['hora_visita_preferida'] ?? null;
        
        // Validaciones
        if (!$propiedadId) {
            $this->jsonResponse(['success' => false, 'message' => 'Propiedad no válida']);
        }
        
        // Verificar si la propiedad existe y está activa
        $propiedad = $this->propertyModel->getById($propiedadId);
        if (!$propiedad || $propiedad['estado'] !== 'activa') {
            $this->jsonResponse(['success' => false, 'message' => 'La propiedad no está disponible']);
        }
        
        // Verificar si ya existe una solicitud activa
        $solicitudExistente = $this->solicitudModel->verificarSolicitudExistente($_SESSION['user_id'], $propiedadId);
        if ($solicitudExistente) {
            $this->jsonResponse(['success' => false, 'message' => 'Ya tiene una solicitud activa para esta propiedad']);
        }
        
        // Obtener el agente asignado a la propiedad
        $agenteId = $this->solicitudModel->getAgentePropiedad($propiedadId);
        
        // Preparar datos para la solicitud
        $datosSolicitud = [
            'cliente_id' => $_SESSION['user_id'],
            'propiedad_id' => $propiedadId,
            'agente_id' => $agenteId,
            'mensaje' => $mensaje ?: null,
            'telefono_contacto' => $telefonoContacto ?: null,
            'precio_ofrecido' => $precioOfrecido ?: null,
            'fecha_visita_preferida' => $fechaVisita ?: null,
            'hora_visita_preferida' => $horaVisita ?: null
        ];
        
        // Crear la solicitud
        if ($this->solicitudModel->crearSolicitud($datosSolicitud)) {
            $this->jsonResponse(['success' => true, 'message' => 'Solicitud enviada correctamente']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al enviar la solicitud']);
        }
    }
    
    /**
     * Mostrar solicitudes del cliente
     */
    public function misSolicitudes() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
            $this->redirect('/auth/login');
        }
        
        $solicitudes = $this->solicitudModel->getSolicitudesPorCliente($_SESSION['user_id']);
        
        $this->view('solicitudes/mis_solicitudes', [
            'solicitudes' => $solicitudes
        ]);
    }
    
    /**
     * Mostrar solicitudes asignadas al agente
     */
    public function solicitudesAgente() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
            $this->redirect('/auth/login');
        }
        
        $estado = $_GET['estado'] ?? null;
        $solicitudes = $this->solicitudModel->getSolicitudesPorAgente($_SESSION['user_id'], $estado);
        
        $this->view('solicitudes/solicitudes_agente', [
            'solicitudes' => $solicitudes,
            'estado_filtro' => $estado
        ]);
    }
    
    /**
     * Ver detalles de una solicitud específica
     */
    public function ver() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
        }
        
        $solicitudId = $_GET['id'] ?? null;
        
        if (!$solicitudId) {
            $this->redirect('/dashboard');
        }
        
        $solicitud = $this->solicitudModel->getSolicitudCompleta($solicitudId);
        
        if (!$solicitud) {
            $this->redirect('/dashboard');
        }
        
        // Verificar permisos: solo el cliente, agente asignado o admin pueden ver
        $puedeVer = false;
        if ($_SESSION['role'] === 'admin') {
            $puedeVer = true;
        } elseif ($_SESSION['role'] === 'agent' && $solicitud['agente_id'] == $_SESSION['user_id']) {
            $puedeVer = true;
        } elseif ($_SESSION['role'] === 'client' && $solicitud['cliente_id'] == $_SESSION['user_id']) {
            $puedeVer = true;
        }
        
        if (!$puedeVer) {
            $this->redirect('/dashboard');
        }
        
        $this->view('solicitudes/detalle', [
            'solicitud' => $solicitud
        ]);
    }
    
    /**
     * Actualizar estado de una solicitud (solo agentes y admin)
     */
    public function actualizarEstado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }
        
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['agent', 'admin'])) {
            $this->jsonResponse(['success' => false, 'message' => 'No tiene permisos']);
        }
        
        $solicitudId = $_POST['solicitud_id'] ?? null;
        $nuevoEstado = $_POST['nuevo_estado'] ?? null;
        $notasAgente = trim($_POST['notas_agente'] ?? '');
        
        if (!$solicitudId || !$nuevoEstado) {
            $this->jsonResponse(['success' => false, 'message' => 'Datos incompletos']);
        }
        
        // Validar estado
        $estadosValidos = ['nuevo', 'en_revision', 'cita_agendada', 'cerrado'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            $this->jsonResponse(['success' => false, 'message' => 'Estado no válido']);
        }
        
        // Verificar que la solicitud existe y el usuario tiene permisos
        $solicitud = $this->solicitudModel->getSolicitudCompleta($solicitudId);
        if (!$solicitud) {
            $this->jsonResponse(['success' => false, 'message' => 'Solicitud no encontrada']);
        }
        
        // Verificar permisos
        if ($_SESSION['role'] === 'agent' && $solicitud['agente_id'] != $_SESSION['user_id']) {
            $this->jsonResponse(['success' => false, 'message' => 'No tiene permisos para esta solicitud']);
        }
        
        // Actualizar estado
        if ($this->solicitudModel->actualizarEstado($solicitudId, $nuevoEstado, $notasAgente)) {
            $this->jsonResponse(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
    }
    
    /**
     * Asignar agente a una solicitud (solo admin)
     */
    public function asignarAgente() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->jsonResponse(['success' => false, 'message' => 'No tiene permisos']);
        }
        
        $solicitudId = $_POST['solicitud_id'] ?? null;
        $agenteId = $_POST['agente_id'] ?? null;
        
        if (!$solicitudId || !$agenteId) {
            $this->jsonResponse(['success' => false, 'message' => 'Datos incompletos']);
        }
        
        // Verificar que el agente existe y es un agente
        $agente = $this->userModel->getById($agenteId);
        if (!$agente || $agente['role'] !== 'agent') {
            $this->jsonResponse(['success' => false, 'message' => 'Agente no válido']);
        }
        
        // Asignar agente
        if ($this->solicitudModel->asignarAgente($solicitudId, $agenteId)) {
            $this->jsonResponse(['success' => true, 'message' => 'Agente asignado correctamente']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al asignar el agente']);
        }
    }
    
    /**
     * Obtener estadísticas de solicitudes para dashboard
     */
    public function estadisticas() {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'No autenticado']);
        }
        
        $agenteId = null;
        if ($_SESSION['role'] === 'agent') {
            $agenteId = $_SESSION['user_id'];
        }
        
        $estadisticas = $this->solicitudModel->getEstadisticasSolicitudes($agenteId);
        
        $this->jsonResponse(['success' => true, 'data' => $estadisticas]);
    }
    
    /**
     * Buscar solicitudes con filtros
     */
    public function buscar() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['agent', 'admin'])) {
            $this->redirect('/auth/login');
        }
        
        $filtros = [
            'estado' => $_GET['estado'] ?? null,
            'agente_id' => $_SESSION['role'] === 'agent' ? $_SESSION['user_id'] : ($_GET['agente_id'] ?? null),
            'fecha_desde' => $_GET['fecha_desde'] ?? null,
            'fecha_hasta' => $_GET['fecha_hasta'] ?? null,
            'limite' => 50
        ];
        
        $solicitudes = $this->solicitudModel->buscarSolicitudes($filtros);
        
        $this->view('solicitudes/buscar', [
            'solicitudes' => $solicitudes,
            'filtros' => $filtros
        ]);
    }
    
    /**
     * Cancelar una solicitud (solo el cliente que la creó)
     */
    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
            $this->jsonResponse(['success' => false, 'message' => 'No tiene permisos']);
        }
        
        $solicitudId = $_POST['solicitud_id'] ?? null;
        
        if (!$solicitudId) {
            $this->jsonResponse(['success' => false, 'message' => 'Solicitud no válida']);
        }
        
        // Verificar que la solicitud existe y pertenece al cliente
        $solicitud = $this->solicitudModel->getSolicitudCompleta($solicitudId);
        if (!$solicitud || $solicitud['cliente_id'] != $_SESSION['user_id']) {
            $this->jsonResponse(['success' => false, 'message' => 'Solicitud no encontrada']);
        }
        
        // Solo se puede cancelar si está en estado 'nuevo'
        if ($solicitud['estado'] !== 'nuevo') {
            $this->jsonResponse(['success' => false, 'message' => 'No se puede cancelar una solicitud en proceso']);
        }
        
        // Cambiar estado a 'cerrado'
        if ($this->solicitudModel->actualizarEstado($solicitudId, 'cerrado', 'Cancelada por el cliente')) {
            $this->jsonResponse(['success' => true, 'message' => 'Solicitud cancelada correctamente']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al cancelar la solicitud']);
        }
    }
} 