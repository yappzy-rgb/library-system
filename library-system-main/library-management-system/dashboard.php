<?php
$page_title = "Dashboard";
require_once 'config/config.php';

$total_books = $conn->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$borrowed_books = $conn->query("SELECT COUNT(*) as count FROM borrow_records WHERE status='Borrowed'")->fetch_assoc()['count'];
$available_books = $conn->query("SELECT SUM(available_copies) as total FROM books")->fetch_assoc()['total'];
?>

<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary mb-3"><i class="fas fa-tachometer-alt me-2"></i>Library Dashboard</h2>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card text-center h-100 fade-in">
            <div class="card-body">
                <i class="fas fa-book fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold"><?php echo $total_books; ?></h3>
                <p class="text-muted mb-0">Total Books</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card text-center h-100 fade-in">
            <div class="card-body">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h3 class="fw-bold"><?php echo $available_books ?? 0; ?></h3>
                <p class="text-muted mb-0">Available Books</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card text-center h-100 fade-in">
            <div class="card-body">
                <i class="fas fa-hand-holding fa-3x text-warning mb-3"></i>
                <h3 class="fw-bold"><?php echo $borrowed_books; ?></h3>
                <p class="text-muted mb-0">Books Borrowed</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card text-center h-100 fade-in">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-info mb-3"></i>
                <h3 class="fw-bold"><?php echo $total_students; ?></h3>
                <p class="text-muted mb-0">Total Students</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Recent Borrow Records</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_borrows = $conn->query("
                                SELECT b.*, s.full_name, bk.title 
                                FROM borrow_records b 
                                JOIN students s ON b.student_number = s.student_number 
                                JOIN books bk ON b.book_id = bk.book_id 
                                ORDER BY b.borrow_date DESC LIMIT 5
                            ");
                            if ($recent_borrows->num_rows > 0) {
                                while ($row = $recent_borrows->fetch_assoc()) {
                                    $status_class = $row['status'] == 'Borrowed' ? 'text-warning' : 'text-success';
                                    echo "<tr>
                                        <td>{$row['full_name']}</td>
                                        <td>{$row['title']}</td>
                                        <td>" . date('M j, Y', strtotime($row['borrow_date'])) . "</td>
                                        <td>" . date('M j, Y', strtotime($row['due_date'])) . "</td>
                                        <td><span class='badge bg-warning text-dark'>{$row['status']}</span></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted py-4'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

