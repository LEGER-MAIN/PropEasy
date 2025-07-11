<?php
/**
 * Configuración de rutas de la aplicación PropEasy
 */

return [
    // Rutas principales
    '/' => 'HomeController@index',
    '/home' => 'HomeController@index',
    
    // Rutas de autenticación
    '/login' => 'AuthController@login',
    '/register' => 'AuthController@register',
    '/logout' => 'AuthController@logout',
    '/auth/login' => 'AuthController@login',
    '/auth/register' => 'AuthController@register',
    '/auth/logout' => 'AuthController@logout',
    '/auth/process-login' => 'AuthController@processLogin',
    '/auth/process-register' => 'AuthController@processRegister',
    '/auth/forgot-password' => 'AuthController@forgotPassword',
    '/auth/reset-password' => 'AuthController@resetPassword',
    '/auth/validate' => 'AuthController@validate',
    
    // Rutas de propiedades
    '/properties' => 'PropertiesController@index',
    '/properties/index' => 'PropertiesController@index',
    '/properties/detail/{id}' => 'PropertiesController@detail',
    '/properties/contact' => 'PropertiesController@processContact',
    '/properties/appointment' => 'PropertiesController@processAppointment',
    '/properties/report' => 'PropertiesController@processReport',
    '/properties/favorite' => 'PropertiesController@favorite',
    '/properties/favorites' => 'PropertiesController@favorites',
    '/properties/stats/{id}' => 'PropertiesController@stats',
    '/properties/search' => 'PropertiesController@search',
    '/properties/filter' => 'PropertiesController@filter',
    '/properties/publish' => 'PropertiesController@publish',
    '/properties/process-publish' => 'PropertiesController@processPublish',
    '/properties/pending' => 'PropertiesController@pending',
    '/properties/validate/{id}' => 'PropertiesController@validate',
    '/properties/process-validate' => 'PropertiesController@processValidate',
    
    // Rutas de agentes
    '/agent' => 'AgentController@index',
    '/agent/dashboard' => 'AgentController@dashboard',
    '/agent/properties' => 'AgentController@properties',
    '/agent/clients' => 'AgentController@clients',
    '/agent/appointments' => 'AgentController@appointments',
    '/agent/messages' => 'AgentController@messages',
    '/agent/stats' => 'AgentController@stats',
    '/agent/profile' => 'AgentController@profile',
    '/agent/settings' => 'AgentController@settings',
    '/agent/api/stats' => 'AgentController@getStats',
    '/agent/api/properties' => 'AgentController@propertiesApi',
    '/agent/api/unassigned-properties' => 'AgentController@unassignedProperties',
    '/agent/mark-message-read' => 'AgentController@markMessageAsRead',
    '/agent/confirm-appointment' => 'AgentController@confirmAppointment',
    
    // Rutas de dashboard
    '/dashboard' => 'DashboardController@index',
    '/dashboard/admin' => 'DashboardController@admin',
    '/dashboard/agent' => 'DashboardController@agent',
    
    // Rutas de contacto
    '/contact' => 'ContactController@index',
    '/contact/send' => 'ContactController@send',
    '/contact/info' => 'ContactController@info',
    '/contact/faqs' => 'ContactController@faqs',
    '/contact/newsletter' => 'ContactController@newsletter',
    '/contact/report' => 'ContactController@report',
    '/contact/callback' => 'ContactController@callback',
    
    // Rutas de información
    '/about' => 'AboutController@index',
    '/about/info' => 'AboutController@info',
    '/about/stats' => 'AboutController@stats',
    '/about/testimonials' => 'AboutController@testimonials',
    '/about/team' => 'AboutController@team',
    '/about/awards' => 'AboutController@awards',
    '/about/values' => 'AboutController@values',
    '/about/all' => 'AboutController@all',
    '/about/add-testimonial' => 'AboutController@addTestimonial',
    '/about/corporate-contact' => 'AboutController@corporateContact',
    
    // Rutas de blog
    '/blog' => 'BlogController@index',
    '/blog/post/{id}' => 'BlogController@post',
    '/blog/category/{category}' => 'BlogController@category',
    '/blog/search' => 'BlogController@search',
    
    // Rutas de carreras
    '/careers' => 'CareersController@index',
    '/careers/apply' => 'CareersController@apply',
    '/careers/job/{id}' => 'CareersController@job',
    
    // Rutas de FAQ
    '/faq' => 'FaqController@index',
    '/faq/category/{category}' => 'FaqController@category',
    '/faq/search' => 'FaqController@search',
    
    // Rutas de privacidad y términos
    '/privacy' => 'PrivacyController@index',
    '/terms' => 'TermsController@index',
    
    // Rutas de sitemap
    '/sitemap' => 'SitemapController@index',
    '/sitemap.xml' => 'SitemapController@xml',
    
    // Rutas de solicitudes de compra
    '/solicitud-compra' => 'SolicitudCompraController@index',
    '/solicitud-compra/process' => 'SolicitudCompraController@process',
    '/solicitud-compra/success' => 'SolicitudCompraController@success',
    '/solicitud-compra/status/{id}' => 'SolicitudCompraController@status',
    
    // Rutas API
    '/api/properties' => 'ApiController@properties',
    '/api/properties/{id}' => 'ApiController@property',
    '/api/agents' => 'ApiController@agents',
    '/api/agent/{id}' => 'ApiController@agent',
    '/api/stats' => 'ApiController@stats',
    '/api/search' => 'ApiController@search',
    
    // Rutas de error
    '/404' => 'ErrorController@notFound',
    '/500' => 'ErrorController@serverError',
    '/error' => 'ErrorController@index',
    
    // Rutas de mantenimiento
    '/maintenance' => 'MaintenanceController@index',
    '/health' => 'HealthController@check',
    
    // Rutas de administración
    '/admin' => 'AdminController@index',
    '/admin/users' => 'AdminController@users',
    '/admin/properties' => 'AdminController@properties',
    '/admin/agents' => 'AdminController@agents',
    '/admin/reports' => 'AdminController@reports',
    '/admin/settings' => 'AdminController@settings',
    '/admin/backup' => 'AdminController@backup',
    '/admin/logs' => 'AdminController@logs'
];
?> 