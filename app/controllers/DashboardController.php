<?php
require_once __DIR__ . '/../../core/Controller.php';

class DashboardController extends Controller {
    
    public function admin() {
        $data = [
            'titulo' => 'Dashboard Administrador - PropEasy',
            'stats' => $this->getAdminStats(),
            'recentProperties' => $this->getRecentProperties(),
            'recentUsers' => $this->getRecentUsers(),
            'recentReports' => $this->getRecentReports()
        ];
        
        $this->view('dashboard/admin', $data);
    }
    
    public function agent() {
        $data = [
            'titulo' => 'Dashboard Agente - PropEasy',
            'stats' => $this->getAgentStats(),
            'myProperties' => $this->getAgentProperties(),
            'recentAppointments' => $this->getRecentAppointments(),
            'recentMessages' => $this->getRecentMessages()
        ];
        
        $this->view('dashboard/agent', $data);
    }
    
    public function client() {
        $data = [
            'titulo' => 'Dashboard Cliente - PropEasy',
            'stats' => $this->getClientStats(),
            'favoriteProperties' => $this->getFavoriteProperties(),
            'myRequests' => $this->getClientRequests(),
            'recentAppointments' => $this->getClientAppointments()
        ];
        
        $this->view('dashboard/client', $data);
    }
    
    // Estadísticas para el administrador
    private function getAdminStats() {
        return [
            'total_properties' => 156,
            'active_properties' => 142,
            'sold_properties' => 14,
            'total_agents' => 8,
            'total_clients' => 234,
            'total_sales' => 45000000,
            'total_appointments' => 89,
            'completed_appointments' => 67,
            'total_reports' => 12,
            'pending_reports' => 3,
            'properties_this_month' => 23,
            'sales_this_month' => 8500000
        ];
    }
    
    // Estadísticas para el agente
    private function getAgentStats() {
        return [
            'my_properties' => 18,
            'active_properties' => 15,
            'sold_properties' => 3,
            'total_clients' => 45,
            'active_clients' => 23,
            'total_appointments' => 34,
            'completed_appointments' => 28,
            'pending_appointments' => 6,
            'total_commission' => 450000,
            'commission_this_month' => 125000,
            'response_rate' => 94,
            'client_satisfaction' => 4.8
        ];
    }
    
    // Estadísticas para el cliente
    private function getClientStats() {
        return [
            'favorite_properties' => 12,
            'viewed_properties' => 45,
            'total_requests' => 8,
            'pending_requests' => 3,
            'total_appointments' => 5,
            'completed_appointments' => 4,
            'saved_searches' => 3,
            'last_activity' => '2025-01-07'
        ];
    }
    
    // Propiedades recientes para admin
    private function getRecentProperties() {
        return [
            [
                'id' => 1,
                'title' => 'Casa Moderna en Santo Domingo Este',
                'price' => 2500000,
                'agent' => 'María González',
                'status' => 'Activa',
                'date' => '2025-01-07'
            ],
            [
                'id' => 2,
                'title' => 'Apartamento de Lujo en Piantini',
                'price' => 1800000,
                'agent' => 'Carlos Rodríguez',
                'status' => 'Activa',
                'date' => '2025-01-06'
            ],
            [
                'id' => 3,
                'title' => 'Terreno Comercial en Santiago',
                'price' => 3200000,
                'agent' => 'Ana Martínez',
                'status' => 'En Revisión',
                'date' => '2025-01-05'
            ]
        ];
    }
    
    // Usuarios recientes para admin
    private function getRecentUsers() {
        return [
            [
                'id' => 1,
                'name' => 'Juan Pérez',
                'email' => 'juan@email.com',
                'type' => 'Cliente',
                'status' => 'Activo',
                'date' => '2025-01-07'
            ],
            [
                'id' => 2,
                'name' => 'Laura Sánchez',
                'email' => 'laura@email.com',
                'type' => 'Agente',
                'status' => 'Activo',
                'date' => '2025-01-06'
            ],
            [
                'id' => 3,
                'name' => 'Roberto Díaz',
                'email' => 'roberto@email.com',
                'type' => 'Cliente',
                'status' => 'Pendiente',
                'date' => '2025-01-05'
            ]
        ];
    }
    
    // Reportes recientes para admin
    private function getRecentReports() {
        return [
            [
                'id' => 1,
                'type' => 'Irregularidad',
                'description' => 'Problema con agente en visita',
                'status' => 'Pendiente',
                'date' => '2025-01-07'
            ],
            [
                'id' => 2,
                'type' => 'Sugerencia',
                'description' => 'Mejora en la plataforma',
                'status' => 'En Revisión',
                'date' => '2025-01-06'
            ],
            [
                'id' => 3,
                'type' => 'Queja',
                'description' => 'Información incorrecta',
                'status' => 'Resuelto',
                'date' => '2025-01-05'
            ]
        ];
    }
    
    // Propiedades del agente
    private function getAgentProperties() {
        return [
            [
                'id' => 1,
                'title' => 'Casa Familiar en Bella Vista',
                'price' => 3500000,
                'status' => 'Activa',
                'views' => 45,
                'inquiries' => 8
            ],
            [
                'id' => 2,
                'title' => 'Apartamento Studio en Zona Colonial',
                'price' => 1200000,
                'status' => 'Activa',
                'views' => 32,
                'inquiries' => 5
            ],
            [
                'id' => 3,
                'title' => 'Local Comercial en Plaza Central',
                'price' => 4500000,
                'status' => 'Vendida',
                'views' => 67,
                'inquiries' => 12
            ]
        ];
    }
    
    // Citas recientes del agente
    private function getRecentAppointments() {
        return [
            [
                'id' => 1,
                'client' => 'María López',
                'property' => 'Casa Familiar en Bella Vista',
                'date' => '2025-01-08',
                'time' => '15:00',
                'status' => 'Confirmada'
            ],
            [
                'id' => 2,
                'client' => 'Carlos Ruiz',
                'property' => 'Apartamento Studio en Zona Colonial',
                'date' => '2025-01-09',
                'time' => '10:30',
                'status' => 'Pendiente'
            ],
            [
                'id' => 3,
                'client' => 'Ana García',
                'property' => 'Casa Familiar en Bella Vista',
                'date' => '2025-01-07',
                'time' => '14:00',
                'status' => 'Completada'
            ]
        ];
    }
    
    // Mensajes recientes del agente
    private function getRecentMessages() {
        return [
            [
                'id' => 1,
                'client' => 'María López',
                'property' => 'Casa Familiar en Bella Vista',
                'message' => '¿Podemos agendar una visita para mañana?',
                'date' => '2025-01-07 14:30',
                'unread' => true
            ],
            [
                'id' => 2,
                'client' => 'Carlos Ruiz',
                'property' => 'Apartamento Studio en Zona Colonial',
                'message' => 'Me interesa mucho esta propiedad',
                'date' => '2025-01-07 12:15',
                'unread' => false
            ]
        ];
    }
    
    // Propiedades favoritas del cliente
    private function getFavoriteProperties() {
        return [
            [
                'id' => 1,
                'title' => 'Casa Moderna en Santo Domingo Este',
                'price' => 2500000,
                'agent' => 'María González',
                'date_added' => '2025-01-05'
            ],
            [
                'id' => 2,
                'title' => 'Apartamento de Lujo en Piantini',
                'price' => 1800000,
                'agent' => 'Carlos Rodríguez',
                'date_added' => '2025-01-04'
            ]
        ];
    }
    
    // Solicitudes del cliente
    private function getClientRequests() {
        return [
            [
                'id' => 1,
                'type' => 'Compra',
                'property' => 'Casa Moderna en Santo Domingo Este',
                'status' => 'En Revisión',
                'date' => '2025-01-06'
            ],
            [
                'id' => 2,
                'type' => 'Venta',
                'property' => 'Mi Apartamento en Centro',
                'status' => 'Pendiente',
                'date' => '2025-01-05'
            ]
        ];
    }
    
    // Citas del cliente
    private function getClientAppointments() {
        return [
            [
                'id' => 1,
                'agent' => 'María González',
                'property' => 'Casa Moderna en Santo Domingo Este',
                'date' => '2025-01-08',
                'time' => '15:00',
                'status' => 'Confirmada'
            ],
            [
                'id' => 2,
                'agent' => 'Carlos Rodríguez',
                'property' => 'Apartamento de Lujo en Piantini',
                'date' => '2025-01-10',
                'time' => '11:00',
                'status' => 'Pendiente'
            ]
        ];
    }
} 