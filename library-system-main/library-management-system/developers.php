<?php
$page_title = "Developers";
?>

<?php include 'includes/header.php'; ?>

<div class="row mb-5">
    <div class="col-12 text-center">
        <h2 class="fw-bold text-primary mb-4"><i class="fas fa-users me-2"></i>Meet Our Development Team</h2>
        <p class="lead text-muted">The talented individuals who built this Library Management System</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Developer 1 -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <!-- Developer Image (200x200px recommended) -->
                <div class="developer-image mb-4 mx-auto">
                    <img src="assets/images/zeus.png" alt="Zeus Macatlang" class="rounded-circle img-fluid shadow" 
                         style="width: 140px; height: 140px; object-fit: cover;" 
                         onerror="this.src='https://via.placeholder.com/140x140/0d6efd/ffffff?text=JD'">
                </div>
                <h4 class="card-title fw-bold text-primary mb-1">Zeus Macatlang</h4>
                <h6 class="text-primary mb-3">Lead Developer</h6>
                <p class="card-text text-muted mb-4">
                    Full-stack developer responsible for system architecture, database design, 
                    and core PHP/MySQL functionality. Passionate about clean code.
                </p>
                <div class="mb-4">
                    <span class="badge bg-primary me-2 mb-1 d-block">PHP</span>
                    <span class="badge bg-success me-2 mb-1 d-block">MySQL</span>
                    <span class="badge bg-info me-2 mb-1 d-block">Bootstrap</span>
                    <span class="badge bg-warning me-2 mb-1 d-block">JavaScript</span>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fab fa-github me-1"></i>GitHub
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm">
                        <i class="fab fa-linkedin me-1"></i>LinkedIn
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer 2 -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <!-- Developer Image -->
                <div class="developer-image mb-4 mx-auto">
                    <img src="assets/images/lujelle.jpg" alt="Lujelle Violante" class="rounded-circle img-fluid shadow" 
                         style="width: 140px; height: 140px; object-fit: cover;" 
                         onerror="this.src='https://via.placeholder.com/140x140/28a745/ffffff?text=JS'">
                </div>
                <h4 class="card-title fw-bold text-success mb-1">Lujelle Violante</h4>
                <h6 class="text-success mb-3">UI/UX Designer</h6>
                <p class="card-text text-muted mb-4">
                    Frontend specialist and UI/UX designer. Created the responsive Bootstrap 
                    interface and modern academic design theme.
                </p>
                <div class="mb-4">
                    <span class="badge bg-info me-2 mb-1 d-block">HTML/CSS</span>
                    <span class="badge bg-warning me-2 mb-1 d-block">Bootstrap 5</span>
                    <span class="badge bg-light text-dark me-2 mb-1 d-block">Figma</span>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="btn btn-outline-info btn-sm">
                        <i class="fab fa-dribbble me-1"></i>Dribbble
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fab fa-linkedin me-1"></i>LinkedIn
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
      
                <div class="developer-image mb-4 mx-auto">
                    <img src="assets/images/jm.png" alt="JM Coronado" class="rounded-circle img-fluid shadow" 
                         style="width: 140px; height: 140px; object-fit: cover;" 
                         onerror="this.src='https://via.placeholder.com/140x140/dc3545/ffffff?text=MJ'">
                </div>
                <h4 class="card-title fw-bold text-danger mb-1">JM Coronado</h4>
                <h6 class="text-danger mb-3">Database Architect</h6>
                <p class="card-text text-muted mb-4">
                    Database administrator and backend specialist. Designed optimized MySQL 
                    schema and complex query relationships for performance.
                </p>
                <div class="mb-4">
                    <span class="badge bg-secondary me-2 mb-1 d-block">MySQL</span>
                    <span class="badge bg-dark text-white me-2 mb-1 d-block">SQL</span>
                    <span class="badge bg-primary me-2 mb-1 d-block">PHP</span>
                    <span class="badge bg-success me-2 mb-1 d-block">phpMyAdmin</span>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="fab fa-github me-1"></i>GitHub
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-envelope me-1"></i>Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- NAVBAR MOBILE FIX -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.navbar-toggler')?.addEventListener('click', function() {
        document.querySelector('#navbarNav')?.classList.toggle('show');
    });
});
</script>




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