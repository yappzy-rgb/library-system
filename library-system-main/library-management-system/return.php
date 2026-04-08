<?php
$page_title = "Return Book";
require_once 'config/config.php';

$message = '';
$message_type = '';

// Handle return
if (isset($_POST['return_book'])) {
    $borrow_id = (int)$_POST['borrow_id'];
    
    // Get book_id and check if borrowed
    $stmt = $conn->prepare("SELECT br.book_id, b.available_copies FROM borrow_records br JOIN books b ON br.book_id = b.book_id WHERE br.borrow_id = ? AND br.status = 'Borrowed'");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $book_id = $row['book_id'];
        
        // Update borrow record
        $return_date = date('Y-m-d');
        $stmt = $conn->prepare("UPDATE borrow_records SET status = 'Returned', return_date = ? WHERE borrow_id = ?");
        $stmt->bind_param("si", $return_date, $borrow_id);
        $stmt->execute();
        
        // Update book available copies
        $new_available = $row['available_copies'] + 1;
        $stmt = $conn->prepare("UPDATE books SET available_copies = ? WHERE book_id = ?");
        $stmt->bind_param("ii", $new_available, $book_id);
        $stmt->execute();
        
        $message = "Book returned successfully!";
        $message_type = "success";
    } else {
        $message = "Invalid borrow record or already returned.";
        $message_type = "danger";
    }
}

// Get active borrows
$borrows_result = $conn->query("
    SELECT br.*, s.full_name, s.course, b.title, b.author 
    FROM borrow_records br 
    JOIN students s ON br.student_number = s.student_number 
    JOIN books b ON br.book_id = b.book_id 
    WHERE br.status = 'Borrowed' 
    ORDER BY br.borrow_date DESC
");
?>

<?php include 'includes/header.php'; ?>

<?php if ($message): ?>
<div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary mb-3"><i class="fas fa-undo me-2"></i>Return Book</h2>
        <p class="text-muted">Currently <?php echo $borrows_result->num_rows; ?> books borrowed</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Borrowed Books</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Borrow ID</th>
                        <th>Student</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Days Overdue</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($borrows_result->num_rows > 0): ?>
                        <?php while ($borrow = $borrows_result->fetch_assoc()): ?>
                            <?php 
                            $due_date = new DateTime($borrow['due_date']);
                            $today = new DateTime();
                            $overdue_days = $due_date->diff($today)->days * ($today > $due_date ? 1 : 0);
                            $overdue_class = $overdue_days > 0 ? 'text-danger fw-bold' : '';
                            ?>
                        <tr>
                            <td><span class="badge bg-primary"><?php echo $borrow['borrow_id']; ?></span></td>
                            <td>
                                <strong><?php echo htmlspecialchars($borrow['full_name']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($borrow['course']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($borrow['title']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($borrow['author']); ?></small>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($borrow['borrow_date'])); ?></td>
                            <td><?php echo date('M j, Y', strtotime($borrow['due_date'])); ?></td>
                            <td>
                                <span class="<?php echo $overdue_class; ?>">
                                    <?php echo $overdue_days > 0 ? $overdue_days . ' days' : 'On time'; ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="borrow_id" value="<?php echo $borrow['borrow_id']; ?>">
                                    <button type="submit" name="return_book" class="btn btn-sm btn-success" 
                                            onclick="return confirm('Return this book?')">
                                        <i class="fas fa-check"></i> Return
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-3x mb-3 text-success opacity-50"></i><br>
                                No borrowed books. All books are available!
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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