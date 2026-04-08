<?php
$page_title = "About Project";
?>

<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-2"></i>About Library Management System</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Project Overview</h5>
            </div>
            <div class="card-body">
                <p class="lead">
                    A complete <strong>Library Management System</strong> built with modern PHP, MySQL, and Bootstrap.
                    Designed for academic use with clean, professional UI and full CRUD functionality.
                </p>
                
                <div class="row text-center mb-4">
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-code fa-3x text-primary mb-2"></i>
                        <h6>PHP & MySQL</h6>
                        <small class="text-muted">Full-stack development</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-database fa-3x text-success mb-2"></i>
                        <h6>Database</h6>
                        <small class="text-muted">MySQL with phpMyAdmin</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-desktop fa-3x text-info mb-2"></i>
                        <h6>Responsive UI</h6>
                        <small class="text-muted">Bootstrap 5</small>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 mt-4"><i class="fas fa-list-ul me-2 text-primary"></i>Key Features</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0 px-0 py-2">
                        <i class="fas fa-check-circle text-success me-2"></i>Student auto-fetch by number (0324-0000)
                    </li>
                    <li class="list-group-item border-0 px-0 py-2">
                        <i class="fas fa-check-circle text-success me-2"></i>Complete book inventory management
                    </li>
                    <li class="fas fa-check-circle text-success me-2"></i>Borrow/Return system with copy tracking
                    </li>
                    <li class="list-group-item border-0 px-0 py-2">
                        <i class="fas fa-check-circle text-success me-2"></i>Dashboard with statistics
                    </li>
                    <li class="list-group-item border-0 px-0 py-2">
                        <i class="fas fa-check-circle text-success me-2"></i>Search functionality
                    </li>
                    <li class="list-group-item border-0 px-0 py-2">
                        <i class="fas fa-check-circle text-success me-2"></i>Overdue tracking
                    </li>
                    <li class="list-group-item border-0 px-0 py-2">
                        <i class="fas fa-check-circle text-success me-2"></i>Ready for authentication
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-server me-2"></i>Technology Stack</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-cogs text-primary me-2"></i>Backend</h6>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 mb-1 d-block">PHP 8+</span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 mb-1 d-block">MySQL 5.7+</span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 d-block">MySQLi Prepared Statements</span>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-paint-brush text-info me-2"></i>Frontend</h6>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 mb-1 d-block">Bootstrap 5.1</span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 mb-1 d-block">Font Awesome 6</span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 d-block">Custom CSS</span>
                </div>
                <div>
                    <h6><i class="fas fa-rocket text-warning me-2"></i>Compatibility</h6>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 mb-1 d-block">WAMP Server 3.4.0</span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2 d-block">phpMyAdmin</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const toggler = document.querySelector('.navbar-toggler');
    const navbar = document.querySelector('#navbarNav');
    const links = document.querySelectorAll('.nav-link');

    toggler.addEventListener('click', function() {
        navbar.classList.toggle('show');
    });
    

    links.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 991) { 
                navbar.classList.remove('show');
                toggler.classList.add('collapsed');
            }
        });
    });
    

    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target) && !toggler.contains(e.target)) {
            navbar.classList.remove('show');
        }
    });
});
</script>