<?php
require_once __DIR__ . '/../../core/Model.php';

class AgentModel extends Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtiene un agente por ID
     */
    public function getAgentById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.email as user_email, u.name as user_name 
            FROM agents a 
            LEFT JOIN users u ON a.user_id = u.id 
            WHERE a.id = ? AND a.active = 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }
    
    /**
     * Obtiene un agente por user_id
     */
    public function getAgentByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.email as user_email, u.name as user_name 
            FROM agents a 
            JOIN users u ON a.user_id = u.id 
            WHERE a.user_id = ? AND a.active = 1
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    /**
     * Obtiene todos los agentes activos
     */
    public function getActiveAgents() {
        $sql = "SELECT * FROM agents WHERE active = 1 ORDER BY name ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene agentes con sus estadísticas
     */
    public function getAgentsWithStats() {
        $sql = "SELECT a.*, 
                       (SELECT COUNT(*) FROM properties p WHERE p.agente_id = a.id AND p.estado = 'activa') as active_properties,
                       (SELECT COUNT(*) FROM properties p WHERE p.agente_id = a.id AND p.estado = 'vendida') as sold_properties,
                       (SELECT COUNT(*) FROM property_contacts pc 
                        JOIN properties p ON pc.property_id = p.id 
                        WHERE p.agente_id = a.id) as total_contacts,
                       (SELECT COUNT(*) FROM property_appointments pa 
                        JOIN properties p ON pa.property_id = p.id 
                        WHERE p.agente_id = a.id) as total_appointments
                FROM agents a 
                WHERE a.active = 1 
                ORDER BY a.name ASC";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene contactos de un agente
     */
    public function getAgentContacts($agentId, $limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT pc.*, p.titulo as property_title, p.id as property_id
            FROM property_contacts pc
            JOIN properties p ON pc.property_id = p.id
            WHERE p.agente_id = ?
            ORDER BY pc.fecha_creacion DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $agentId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene citas de un agente
     */
    public function getAgentAppointments($agentId, $status = null, $limit = 10, $offset = 0) {
        $sql = "SELECT pa.*, p.titulo as property_title, p.id as property_id
                FROM property_appointments pa
                JOIN properties p ON pa.property_id = p.id
                WHERE p.agente_id = ?";
        
        $params = [$agentId];
        $types = "i";
        
        if ($status) {
            $sql .= " AND pa.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $sql .= " ORDER BY pa.appointment_date DESC, pa.appointment_time DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Actualiza el estado de una cita
     */
    public function updateAppointmentStatus($appointmentId, $status) {
        $stmt = $this->db->prepare("UPDATE property_appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $appointmentId);
        return $stmt->execute();
    }

    /**
     * Marca un contacto como leído
     */
    public function markContactAsRead($contactId) {
        $stmt = $this->db->prepare("UPDATE property_contacts SET status = 'leido' WHERE id = ?");
        $stmt->bind_param("i", $contactId);
        return $stmt->execute();
    }

    /**
     * Obtiene estadísticas de un agente
     */
    public function getAgentStats($agentId) {
        $stats = [];
        
        // Propiedades activas
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM properties WHERE agente_id = ? AND estado = 'activa'");
        $stmt->bind_param("i", $agentId);
        $stmt->execute();
        $stats['active_properties'] = $stmt->get_result()->fetch_assoc()['count'];
        
        // Propiedades vendidas
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM properties WHERE agente_id = ? AND estado = 'vendida'");
        $stmt->bind_param("i", $agentId);
        $stmt->execute();
        $stats['sold_properties'] = $stmt->get_result()->fetch_assoc()['count'];
        
        // Contactos totales
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM property_contacts pc 
            JOIN properties p ON pc.property_id = p.id 
            WHERE p.agente_id = ?
        ");
        $stmt->bind_param("i", $agentId);
        $stmt->execute();
        $stats['total_contacts'] = $stmt->get_result()->fetch_assoc()['count'];
        
        // Citas totales
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM property_appointments pa 
            JOIN properties p ON pa.property_id = p.id 
            WHERE p.agente_id = ?
        ");
        $stmt->bind_param("i", $agentId);
        $stmt->execute();
        $stats['total_appointments'] = $stmt->get_result()->fetch_assoc()['count'];
        
        // Citas pendientes
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM property_appointments pa 
            JOIN properties p ON pa.property_id = p.id 
            WHERE p.agente_id = ? AND pa.status = 'pendiente'
        ");
        $stmt->bind_param("i", $agentId);
        $stmt->execute();
        $stats['pending_appointments'] = $stmt->get_result()->fetch_assoc()['count'];
        
        // Contactos no leídos
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM property_contacts pc 
            JOIN properties p ON pc.property_id = p.id 
            WHERE p.agente_id = ? AND pc.status = 'nuevo'
        ");
        $stmt->bind_param("i", $agentId);
        $stmt->execute();
        $stats['unread_contacts'] = $stmt->get_result()->fetch_assoc()['count'];
        
        return $stats;
    }

    /**
     * Crea o actualiza un agente
     */
    public function saveAgent($data) {
        if (isset($data['id']) && $data['id'] > 0) {
            // Actualizar agente existente
            $stmt = $this->db->prepare("
                UPDATE agents SET 
                    name = ?, email = ?, phone = ?, title = ?, description = ?, 
                    specialties = ?, availability = ?, whatsapp = ?, photo = ?
                WHERE id = ?
            ");
            $stmt->bind_param("sssssssssi", 
                $data['name'], $data['email'], $data['phone'], $data['title'], 
                $data['description'], $data['specialties'], $data['availability'], 
                $data['whatsapp'], $data['photo'], $data['id']
            );
        } else {
            // Crear nuevo agente
            $stmt = $this->db->prepare("
                INSERT INTO agents (name, email, phone, title, description, specialties, availability, whatsapp, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssssssss", 
                $data['name'], $data['email'], $data['phone'], $data['title'], 
                $data['description'], $data['specialties'], $data['availability'], 
                $data['whatsapp'], $data['photo']
            );
        }
        
        return $stmt->execute();
    }

    /**
     * Desactiva un agente
     */
    public function deactivateAgent($id) {
        $stmt = $this->db->prepare("UPDATE agents SET active = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Obtiene el agente por defecto (para propiedades sin agente asignado)
     */
    public function getDefaultAgent() {
        $stmt = $this->db->prepare("SELECT * FROM agents WHERE active = 1 ORDER BY id ASC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

} 