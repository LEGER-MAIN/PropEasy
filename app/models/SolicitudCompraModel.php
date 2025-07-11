<?php
require_once __DIR__ . '/../../core/Model.php';

class SolicitudCompraModel extends Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Crea una nueva solicitud de compra
     */
    public function crearSolicitud($data) {
        $stmt = $this->db->prepare("
            INSERT INTO solicitudes_compra 
            (cliente_id, propiedad_id, agente_id, mensaje, telefono_contacto, precio_ofrecido, fecha_visita_preferida, hora_visita_preferida) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("iiissdss", 
            $data['cliente_id'],
            $data['propiedad_id'], 
            $data['agente_id'],
            $data['mensaje'],
            $data['telefono_contacto'],
            $data['precio_ofrecido'],
            $data['fecha_visita_preferida'],
            $data['hora_visita_preferida']
        );
        
        return $stmt->execute() ? $this->db->insert_id : false;
    }

    /**
     * Obtiene todas las solicitudes de un cliente
     */
    public function getSolicitudesByCliente($clienteId) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   p.titulo as propiedad_titulo,
                   p.precio as propiedad_precio,
                   p.ubicacion as propiedad_ubicacion,
                   p.imagenes as propiedad_imagenes,
                   a.name as agente_nombre,
                   a.phone as agente_telefono,
                   a.email as agente_email
            FROM solicitudes_compra s
            JOIN properties p ON s.propiedad_id = p.id
            LEFT JOIN agents a ON s.agente_id = a.id
            WHERE s.cliente_id = ?
            ORDER BY s.fecha_creacion DESC
        ");
        
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene todas las solicitudes de un agente
     */
    public function getSolicitudesByAgente($agenteId, $estado = null) {
        $sql = "
            SELECT s.*, 
                   p.titulo as propiedad_titulo,
                   p.precio as propiedad_precio,
                   p.ubicacion as propiedad_ubicacion,
                   p.imagenes as propiedad_imagenes,
                   u.name as cliente_nombre,
                   u.email as cliente_email
            FROM solicitudes_compra s
            JOIN properties p ON s.propiedad_id = p.id
            JOIN users u ON s.cliente_id = u.id
            WHERE s.agente_id = ?
        ";
        
        $params = [$agenteId];
        $types = "i";
        
        if ($estado) {
            $sql .= " AND s.estado = ?";
            $params[] = $estado;
            $types .= "s";
        }
        
        $sql .= " ORDER BY s.fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene una solicitud por ID
     */
    public function getSolicitudById($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   p.titulo as propiedad_titulo,
                   p.precio as propiedad_precio,
                   p.ubicacion as propiedad_ubicacion,
                   p.descripcion as propiedad_descripcion,
                   p.imagenes as propiedad_imagenes,
                   p.tipo as propiedad_tipo,
                   p.habitaciones as propiedad_habitaciones,
                   p.banos as propiedad_banos,
                   u.name as cliente_nombre,
                   u.email as cliente_email,
                   u.phone as cliente_telefono,
                   a.name as agente_nombre,
                   a.phone as agente_telefono,
                   a.email as agente_email
            FROM solicitudes_compra s
            JOIN properties p ON s.propiedad_id = p.id
            JOIN users u ON s.cliente_id = u.id
            LEFT JOIN agents a ON s.agente_id = a.id
            WHERE s.id = ?
        ");
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    /**
     * Actualiza el estado de una solicitud
     */
    public function actualizarEstado($id, $estado, $notasAgente = null) {
        if ($notasAgente) {
            $stmt = $this->db->prepare("
                UPDATE solicitudes_compra 
                SET estado = ?, notas_agente = ?, fecha_actualizacion = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmt->bind_param("ssi", $estado, $notasAgente, $id);
        } else {
            $stmt = $this->db->prepare("
                UPDATE solicitudes_compra 
                SET estado = ?, fecha_actualizacion = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmt->bind_param("si", $estado, $id);
        }
        
        return $stmt->execute();
    }

    /**
     * Programa una cita para una solicitud
     */
    public function programarCita($id, $fechaCita, $horaCita, $notasAgente = null) {
        $stmt = $this->db->prepare("
            UPDATE solicitudes_compra 
            SET estado = 'cita_agendada', 
                fecha_visita_preferida = ?, 
                hora_visita_preferida = ?,
                notas_agente = ?,
                fecha_actualizacion = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        
        $stmt->bind_param("sssi", $fechaCita, $horaCita, $notasAgente, $id);
        return $stmt->execute();
    }

    /**
     * Cierra una solicitud
     */
    public function cerrarSolicitud($id, $notasAgente = null) {
        $stmt = $this->db->prepare("
            UPDATE solicitudes_compra 
            SET estado = 'cerrado', 
                notas_agente = ?,
                fecha_cierre = CURRENT_TIMESTAMP,
                fecha_actualizacion = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        
        $stmt->bind_param("si", $notasAgente, $id);
        return $stmt->execute();
    }

    /**
     * Obtiene estadísticas de solicitudes para un agente
     */
    public function getEstadisticasAgente($agenteId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'nuevo' THEN 1 ELSE 0 END) as nuevas,
                SUM(CASE WHEN estado = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
                SUM(CASE WHEN estado = 'cita_agendada' THEN 1 ELSE 0 END) as citas_agendadas,
                SUM(CASE WHEN estado = 'cerrado' THEN 1 ELSE 0 END) as cerradas
            FROM solicitudes_compra 
            WHERE agente_id = ?
        ");
        
        $stmt->bind_param("i", $agenteId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : [
            'total' => 0,
            'nuevas' => 0, 
            'en_revision' => 0,
            'citas_agendadas' => 0,
            'cerradas' => 0
        ];
    }

    /**
     * Obtiene solicitudes recientes
     */
    public function getSolicitudesRecientes($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   p.titulo as propiedad_titulo,
                   u.name as cliente_nombre
            FROM solicitudes_compra s
            JOIN properties p ON s.propiedad_id = p.id
            JOIN users u ON s.cliente_id = u.id
            ORDER BY s.fecha_creacion DESC
            LIMIT ?
        ");
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
} 