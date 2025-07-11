/**
 * JavaScript para la página de Blog
 * Funcionalidades: búsqueda, filtros, paginación, likes, suscripción
 */

// Variables globales
let currentPage = 1;
let currentCategory = '';
let currentSearch = '';
let articlesPerPage = 6;
let allArticles = [];

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeBlog();
    loadArticles();
    setupEventListeners();
});

/**
 * Inicializa la funcionalidad del blog
 */
function initializeBlog() {
    console.log('Inicializando blog...');
    
    // Simular carga de artículos
    allArticles = [
        {
            id: 1,
            title: 'Guía Completa para Comprar tu Primera Casa',
            excerpt: 'Todo lo que necesitas saber antes de dar el paso más importante de tu vida financiera.',
            content: 'Contenido completo del artículo sobre compra de primera casa...',
            author: 'María González',
            author_avatar: 'assets/img/authors/maria.jpg',
            date: '2024-01-15',
            category: 'Compra',
            tags: ['primera casa', 'financiamiento', 'guía'],
            image: 'assets/img/blog/casa-primera.jpg',
            likes: 245,
            views: 1200,
            read_time: 8,
            featured: true
        },
        {
            id: 2,
            title: 'Tendencias del Mercado Inmobiliario 2024',
            excerpt: 'Análisis de las tendencias que marcarán el mercado inmobiliario este año.',
            content: 'Contenido completo sobre tendencias del mercado...',
            author: 'Carlos Rodríguez',
            author_avatar: 'assets/img/authors/carlos.jpg',
            date: '2024-01-10',
            category: 'Mercado',
            tags: ['tendencias', 'mercado', '2024'],
            image: 'assets/img/blog/tendencias-2024.jpg',
            likes: 189,
            views: 890,
            read_time: 6,
            featured: false
        },
        {
            id: 3,
            title: 'Cómo Maximizar el Valor de tu Propiedad',
            excerpt: 'Consejos prácticos para aumentar el valor de tu casa antes de venderla.',
            content: 'Contenido completo sobre maximizar valor...',
            author: 'Ana Martínez',
            author_avatar: 'assets/img/authors/ana.jpg',
            date: '2024-01-08',
            category: 'Venta',
            tags: ['valor', 'renovación', 'venta'],
            image: 'assets/img/blog/maximizar-valor.jpg',
            likes: 156,
            views: 756,
            read_time: 7,
            featured: false
        },
        {
            id: 4,
            title: 'Crédito Hipotecario: Todo lo que Debes Saber',
            excerpt: 'Guía completa sobre cómo obtener y gestionar tu crédito hipotecario.',
            content: 'Contenido completo sobre crédito hipotecario...',
            author: 'Luis Pérez',
            author_avatar: 'assets/img/authors/luis.jpg',
            date: '2024-01-05',
            category: 'Financiamiento',
            tags: ['crédito', 'hipoteca', 'financiamiento'],
            image: 'assets/img/blog/credito-hipotecario.jpg',
            likes: 312,
            views: 1450,
            read_time: 10,
            featured: true
        },
        {
            id: 5,
            title: 'Invertir en Propiedades: Estrategias Rentables',
            excerpt: 'Descubre las mejores estrategias para invertir en bienes raíces.',
            content: 'Contenido completo sobre inversión inmobiliaria...',
            author: 'Patricia Silva',
            author_avatar: 'assets/img/authors/patricia.jpg',
            date: '2024-01-03',
            category: 'Inversión',
            tags: ['inversión', 'rentabilidad', 'estrategias'],
            image: 'assets/img/blog/inversion-propiedades.jpg',
            likes: 278,
            views: 1120,
            read_time: 9,
            featured: false
        },
        {
            id: 6,
            title: 'Diseño Interior: Transforma tu Espacio',
            excerpt: 'Ideas y consejos para renovar y decorar tu hogar sin gastar una fortuna.',
            content: 'Contenido completo sobre diseño interior...',
            author: 'Sofía Herrera',
            author_avatar: 'assets/img/authors/sofia.jpg',
            date: '2024-01-01',
            category: 'Diseño',
            tags: ['diseño', 'decoración', 'renovación'],
            image: 'assets/img/blog/diseno-interior.jpg',
            likes: 198,
            views: 920,
            read_time: 5,
            featured: false
        }
    ];
    
    // Mostrar estadísticas iniciales
    updateStats();
}

/**
 * Configura los event listeners
 */
function setupEventListeners() {
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('search-blog');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            currentSearch = this.value;
            currentPage = 1;
            loadArticles();
        }, 300));
    }
    
    // Filtros de categoría
    const categoryButtons = document.querySelectorAll('.category-filter');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            filterByCategory(category);
        });
    });
    
    // Botón "Ver todas"
    const showAllBtn = document.getElementById('show-all-btn');
    if (showAllBtn) {
        showAllBtn.addEventListener('click', function() {
            currentCategory = '';
            currentSearch = '';
            currentPage = 1;
            loadArticles();
            updateActiveFilter();
        });
    }
    
    // Suscripción al newsletter
    const subscribeForm = document.getElementById('subscribe-form');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            subscribeNewsletter();
        });
    }
}

/**
 * Carga y muestra los artículos
 */
function loadArticles() {
    const articlesContainer = document.getElementById('articles-container');
    if (!articlesContainer) return;
    
    // Mostrar loading
    articlesContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando artículos...</p></div>';
    
    // Filtrar artículos
    let filteredArticles = filterArticles();
    
    // Calcular paginación
    const totalArticles = filteredArticles.length;
    const totalPages = Math.ceil(totalArticles / articlesPerPage);
    const startIndex = (currentPage - 1) * articlesPerPage;
    const endIndex = startIndex + articlesPerPage;
    const articlesToShow = filteredArticles.slice(startIndex, endIndex);
    
    // Renderizar artículos
    setTimeout(() => {
        renderArticles(articlesToShow);
        renderPagination(totalPages);
        updateResultsCount(totalArticles);
    }, 500);
}

/**
 * Filtra los artículos según criterios
 */
function filterArticles() {
    let filtered = allArticles;
    
    // Filtrar por búsqueda
    if (currentSearch) {
        const searchLower = currentSearch.toLowerCase();
        filtered = filtered.filter(article => 
            article.title.toLowerCase().includes(searchLower) ||
            article.excerpt.toLowerCase().includes(searchLower) ||
            article.tags.some(tag => tag.toLowerCase().includes(searchLower))
        );
    }
    
    // Filtrar por categoría
    if (currentCategory) {
        filtered = filtered.filter(article => 
            article.category.toLowerCase() === currentCategory.toLowerCase()
        );
    }
    
    return filtered;
}

/**
 * Renderiza los artículos en el contenedor
 */
function renderArticles(articles) {
    const container = document.getElementById('articles-container');
    if (!container) return;
    
    if (articles.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron artículos</h4>
                <p class="text-muted">Intenta con otros términos de búsqueda o categorías.</p>
                <button class="btn btn-primary" onclick="resetFilters()">
                    <i class="fas fa-redo me-2"></i>Limpiar Filtros
                </button>
            </div>
        `;
        return;
    }
    
    const articlesHTML = articles.map(article => `
        <div class="col-lg-6 col-xl-4 mb-4">
            <article class="card article-card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    <img src="${article.image}" class="card-img-top" alt="${article.title}" style="height: 200px; object-fit: cover;">
                    ${article.featured ? '<span class="badge bg-warning position-absolute top-0 start-0 m-2">Destacado</span>' : ''}
                    <div class="position-absolute top-0 end-0 m-2">
                        <button class="btn btn-sm btn-outline-light" onclick="toggleLike(${article.id})" title="Me gusta">
                            <i class="fas fa-heart ${article.liked ? 'text-danger' : ''}"></i>
                            <span class="ms-1">${article.likes}</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">${article.category}</span>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>${article.read_time} min
                        </small>
                    </div>
                    <h5 class="card-title">
                        <a href="#" onclick="showArticle(${article.id})" class="text-decoration-none">
                            ${article.title}
                        </a>
                    </h5>
                    <p class="card-text text-muted">${article.excerpt}</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img src="${article.author_avatar}" class="rounded-circle me-2" width="30" height="30" alt="${article.author}">
                            <small class="text-muted">${article.author}</small>
                        </div>
                        <small class="text-muted">${formatDate(article.date)}</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            ${article.tags.slice(0, 2).map(tag => `<span class="badge bg-light text-dark">${tag}</span>`).join('')}
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="showArticle(${article.id})">
                            Leer más
                        </button>
                    </div>
                </div>
            </article>
        </div>
    `).join('');
    
    container.innerHTML = articlesHTML;
}

/**
 * Renderiza la paginación
 */
function renderPagination(totalPages) {
    const paginationContainer = document.getElementById('pagination-container');
    if (!paginationContainer || totalPages <= 1) {
        if (paginationContainer) paginationContainer.innerHTML = '';
        return;
    }
    
    let paginationHTML = '<nav aria-label="Paginación del blog"><ul class="pagination justify-content-center">';
    
    // Botón anterior
    paginationHTML += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Números de página
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            paginationHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Botón siguiente
    paginationHTML += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
    
    paginationHTML += '</ul></nav>';
    paginationContainer.innerHTML = paginationHTML;
}

/**
 * Cambia de página
 */
function changePage(page) {
    if (page < 1) return;
    currentPage = page;
    loadArticles();
    
    // Scroll suave hacia arriba
    window.scrollTo({
        top: document.getElementById('articles-container').offsetTop - 100,
        behavior: 'smooth'
    });
}

/**
 * Filtra por categoría
 */
function filterByCategory(category) {
    currentCategory = category;
    currentPage = 1;
    loadArticles();
    updateActiveFilter();
}

/**
 * Actualiza el filtro activo
 */
function updateActiveFilter() {
    // Remover clase activa de todos los botones
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    
    // Agregar clase activa al botón seleccionado
    if (currentCategory) {
        const activeButton = document.querySelector(`[data-category="${currentCategory}"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-outline-primary');
            activeButton.classList.add('btn-primary');
        }
    }
}

/**
 * Muestra un artículo específico
 */
function showArticle(articleId) {
    const article = allArticles.find(a => a.id === articleId);
    if (!article) return;
    
    // Crear modal con el artículo
    const modalHTML = `
        <div class="modal fade" id="articleModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${article.title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="${article.image}" class="img-fluid rounded mb-3" alt="${article.title}">
                        <div class="d-flex align-items-center mb-3">
                            <img src="${article.author_avatar}" class="rounded-circle me-2" width="40" height="40" alt="${article.author}">
                            <div>
                                <strong>${article.author}</strong><br>
                                <small class="text-muted">${formatDate(article.date)} • ${article.read_time} min de lectura</small>
                            </div>
                        </div>
                        <p class="lead">${article.excerpt}</p>
                        <div class="article-content">
                            ${article.content}
                        </div>
                        <div class="mt-3">
                            <strong>Etiquetas:</strong>
                            ${article.tags.map(tag => `<span class="badge bg-light text-dark me-1">${tag}</span>`).join('')}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onclick="shareArticle(${article.id})">
                            <i class="fas fa-share me-2"></i>Compartir
                        </button>
                        <button type="button" class="btn btn-primary" onclick="toggleLike(${article.id})">
                            <i class="fas fa-heart me-2"></i>Me gusta (${article.likes})
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior si existe
    const existingModal = document.getElementById('articleModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Agregar nuevo modal
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('articleModal'));
    modal.show();
    
    // Registrar vista
    registerArticleView(articleId);
}

/**
 * Alterna el estado de "me gusta"
 */
function toggleLike(articleId) {
    const article = allArticles.find(a => a.id === articleId);
    if (!article) return;
    
    // Simular llamada a API
    fetch('/propeasy/public/blog/like', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            article_id: articleId,
            action: article.liked ? 'unlike' : 'like'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            article.liked = !article.liked;
            article.likes += article.liked ? 1 : -1;
            
            // Actualizar UI
            const likeButton = document.querySelector(`[onclick="toggleLike(${articleId})"]`);
            if (likeButton) {
                const icon = likeButton.querySelector('i');
                const count = likeButton.querySelector('span');
                
                if (article.liked) {
                    icon.classList.add('text-danger');
                } else {
                    icon.classList.remove('text-danger');
                }
                count.textContent = article.likes;
            }
            
            showNotification('Artículo actualizado correctamente', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al actualizar el artículo', 'error');
    });
}

/**
 * Suscribe al newsletter
 */
function subscribeNewsletter() {
    const email = document.getElementById('newsletter-email').value;
    const name = document.getElementById('newsletter-name').value;
    
    if (!email || !name) {
        showNotification('Por favor completa todos los campos', 'error');
        return;
    }
    
    // Simular llamada a API
    fetch('/propeasy/public/blog/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email: email,
            name: name
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('¡Te has suscrito correctamente!', 'success');
            document.getElementById('newsletter-email').value = '';
            document.getElementById('newsletter-name').value = '';
        } else {
            showNotification(data.message || 'Error al suscribirse', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al suscribirse', 'error');
    });
}

/**
 * Comparte un artículo
 */
function shareArticle(articleId) {
    const article = allArticles.find(a => a.id === articleId);
    if (!article) return;
    
    const shareData = {
        title: article.title,
        text: article.excerpt,
        url: window.location.href
    };
    
    if (navigator.share) {
        navigator.share(shareData);
    } else {
        // Fallback: copiar al portapapeles
        const textToCopy = `${article.title}\n\n${article.excerpt}\n\n${window.location.href}`;
        navigator.clipboard.writeText(textToCopy).then(() => {
            showNotification('Enlace copiado al portapapeles', 'success');
        });
    }
}

/**
 * Registra una vista de artículo
 */
function registerArticleView(articleId) {
    fetch('/propeasy/public/blog/registerView', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            article_id: articleId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar contador de vistas
            const article = allArticles.find(a => a.id === articleId);
            if (article) {
                article.views++;
            }
        }
    })
    .catch(error => {
        console.error('Error al registrar vista:', error);
    });
}

/**
 * Actualiza las estadísticas
 */
function updateStats() {
    const statsContainer = document.getElementById('blog-stats');
    if (!statsContainer) return;
    
    const totalArticles = allArticles.length;
    const totalLikes = allArticles.reduce((sum, article) => sum + article.likes, 0);
    const totalViews = allArticles.reduce((sum, article) => sum + article.views, 0);
    const categories = [...new Set(allArticles.map(article => article.category))];
    
    statsContainer.innerHTML = `
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-newspaper fa-2x mb-2"></i>
                        <h4 class="mb-0">${totalArticles}</h4>
                        <small>Artículos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-heart fa-2x mb-2"></i>
                        <h4 class="mb-0">${totalLikes}</h4>
                        <small>Me gusta</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-eye fa-2x mb-2"></i>
                        <h4 class="mb-0">${totalViews}</h4>
                        <small>Vistas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 bg-warning text-white">
                    <div class="card-body">
                        <i class="fas fa-tags fa-2x mb-2"></i>
                        <h4 class="mb-0">${categories.length}</h4>
                        <small>Categorías</small>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Actualiza el contador de resultados
 */
function updateResultsCount(total) {
    const resultsCount = document.getElementById('results-count');
    if (resultsCount) {
        resultsCount.textContent = `${total} artículo${total !== 1 ? 's' : ''} encontrado${total !== 1 ? 's' : ''}`;
    }
}

/**
 * Resetea los filtros
 */
function resetFilters() {
    currentSearch = '';
    currentCategory = '';
    currentPage = 1;
    
    // Limpiar campos
    const searchInput = document.getElementById('search-blog');
    if (searchInput) searchInput.value = '';
    
    loadArticles();
    updateActiveFilter();
}

/**
 * Función de debounce para optimizar búsquedas
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Formatea una fecha
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Muestra una notificación
 */
function showNotification(message, type = 'info') {
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Remover automáticamente después de 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

/**
 * Función para probar la funcionalidad del blog
 */
function testBlogFunctionality() {
    console.log('Probando funcionalidad del blog...');
    
    // Probar búsqueda
    currentSearch = 'propiedades';
    loadArticles();
    
    // Probar filtro por categoría
    setTimeout(() => {
        filterByCategory('Compra');
    }, 1000);
    
    // Probar paginación
    setTimeout(() => {
        changePage(2);
    }, 2000);
    
    // Probar like
    setTimeout(() => {
        toggleLike(1);
    }, 3000);
    
    showNotification('Pruebas completadas', 'success');
}

// Exportar funciones para uso global
window.blogFunctions = {
    loadArticles,
    filterByCategory,
    showArticle,
    toggleLike,
    subscribeNewsletter,
    shareArticle,
    resetFilters,
    testBlogFunctionality
}; 