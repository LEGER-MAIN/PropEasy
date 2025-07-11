<?php
require_once __DIR__ . '/../../core/Model.php';

class FavoriteModel extends Model {
    public function __construct() {
        parent::__construct();
    }

    // Agregar a favoritos
    public function addFavorite($userId, $propertyId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO favorites (user_id, property_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $propertyId);
        return $stmt->execute();
    }

    // Quitar de favoritos
    public function removeFavorite($userId, $propertyId) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND property_id = ?");
        $stmt->bind_param("ii", $userId, $propertyId);
        return $stmt->execute();
    }

    // Verificar si ya es favorito
    public function isFavorite($userId, $propertyId) {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND property_id = ?");
        $stmt->bind_param("ii", $userId, $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Contar favoritos por propiedad
    public function countFavorites($propertyId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM favorites WHERE property_id = ?");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? (int)$row['total'] : 0;
    }

    // Obtener todas las propiedades favoritas de un usuario
    public function getUserFavorites($userId, $limit = 20, $offset = 0) {
        $sql = "SELECT p.*, f.created_at as fecha_favorito,
                       (SELECT COUNT(*) FROM favorites f2 WHERE f2.property_id = p.id) as favorites_count
                FROM favorites f 
                JOIN properties p ON f.property_id = p.id 
                WHERE f.user_id = ? AND p.estado = 'activa'
                ORDER BY f.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $userId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $favorites = [];
        while ($row = $result->fetch_assoc()) {
            $favorites[] = $row;
        }
        
        return $favorites;
    }

    // Contar total de favoritos de un usuario
    public function countUserFavorites($userId) {
        $sql = "SELECT COUNT(*) as total 
                FROM favorites f 
                JOIN properties p ON f.property_id = p.id 
                WHERE f.user_id = ? AND p.estado = 'activa'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row ? (int)$row['total'] : 0;
    }

    // Obtener IDs de propiedades favoritas de un usuario (para verificaciones rápidas)
    public function getUserFavoriteIds($userId) {
        $stmt = $this->db->prepare("SELECT property_id FROM favorites WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $favoriteIds = [];
        while ($row = $result->fetch_assoc()) {
            $favoriteIds[] = (int)$row['property_id'];
        }
        
        return $favoriteIds;
    }

    // Toggle favorito (agregar si no existe, quitar si existe)
    public function toggleFavorite($userId, $propertyId) {
        if ($this->isFavorite($userId, $propertyId)) {
            $removed = $this->removeFavorite($userId, $propertyId);
            return [
                'action' => 'removed',
                'success' => $removed,
                'count' => $this->countFavorites($propertyId)
            ];
        } else {
            $added = $this->addFavorite($userId, $propertyId);
            return [
                'action' => 'added',
                'success' => $added,
                'count' => $this->countFavorites($propertyId)
            ];
        }
    }

    // Obtener estadísticas de favoritos
    public function getFavoriteStats($userId) {
        $stats = [
            'total_favorites' => $this->countUserFavorites($userId),
            'recent_favorites' => 0,
            'favorite_types' => []
        ];

        // Favoritos recientes (últimos 7 días)
        $sql = "SELECT COUNT(*) as recent 
                FROM favorites f 
                JOIN properties p ON f.property_id = p.id 
                WHERE f.user_id = ? AND p.estado = 'activa' 
                AND f.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['recent_favorites'] = $row ? (int)$row['recent'] : 0;

        // Tipos de propiedades favoritas
        $sql = "SELECT p.tipo, COUNT(*) as count 
                FROM favorites f 
                JOIN properties p ON f.property_id = p.id 
                WHERE f.user_id = ? AND p.estado = 'activa' 
                GROUP BY p.tipo 
                ORDER BY count DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $stats['favorite_types'][] = $row;
        }

        return $stats;
    }

    // Limpiar favoritos de propiedades inactivas
    public function cleanupInactiveFavorites() {
        $sql = "DELETE f FROM favorites f 
                LEFT JOIN properties p ON f.property_id = p.id 
                WHERE p.id IS NULL OR p.estado != 'activa'";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
} 