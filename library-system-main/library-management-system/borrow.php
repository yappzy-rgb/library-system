<?php
$page_title = "Borrow Book";
require_once 'config/config.php';

$message = '';
$message_type = '';
$student_data = null;
$books_result = null;

if ($_POST) {
    $student_number = trim($_POST['student_number']);

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_number = ?");
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student_data = $result->fetch_assoc();
        
        if (isset($_POST['borrow_book'])) {
            $book_id = (int)$_POST['book_id'];

            $stmt = $conn->prepare("SELECT available_copies FROM books WHERE book_id = ? AND available_copies > 0");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $book_result = $stmt->get_result();
            
            if ($book_result->num_rows > 0) {
                $book = $book_result->fetch_assoc();
                
    
                $borrow_date = date('Y-m-d');
                $due_date = date('Y-m-d', strtotime('+7 days'));
                
                $stmt = $conn->prepare("INSERT INTO borrow_records (student_number, book_id, borrow_date, due_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siss", $student_number, $book_id, $borrow_date, $due_date);
                
                if ($stmt->execute()) {
            
                    $new_available = $book['available_copies'] - 1;
                    $stmt = $conn->prepare("UPDATE books SET available_copies = ? WHERE book_id = ?");
                    $stmt->bind_param("ii", $new_available, $book_id);
                    $stmt->execute();
                    
                    $message = "Book borrowed successfully! Due date: " . date('M j, Y', strtotime($due_date));
                    $message_type = "success";
                } else {
                    $message = "Error borrowing book. Please try again.";
                    $message_type = "danger";
                }
            } else {
                $message = "Selected book is not available!";
                $message_type = "danger";
            }
        }
    } else {
        $message = "Student not found. Please check the student number or register the student first.";
        $message_type = "danger";
    }
}

if ($student_data) {
    $books_result = $conn->query("SELECT * FROM books WHERE available_copies > 0 ORDER BY title");
}
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
        <h2 class="fw-bold text-primary mb-3"><i class="fas fa-hand-holding me-2"></i>Borrow Book</h2>
        <p class="text-muted">Just enter the <strong>student number</strong> and select a book. That's it!</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Enter Student Number</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="studentForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student Number * <small class="text-muted">(Format: 0324-0000)</small></label>
                        <input type="text" class="form-control form-control-lg" name="student_number" 
                               value="<?php echo $student_data ? $student_data['student_number'] : ''; ?>" 
                               pattern="^\d{4}-\d{4}$" required maxlength="10" autofocus>
                        <?php if ($student_data): ?>
                        <div class="form-text text-success">
                            <i class="fas fa-check-circle me-1"></i>Student verified!
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($student_data): ?>
                        <div class="alert alert-success border-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-check fs-4 me-3 text-success"></i>
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($student_data['full_name']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($student_data['course']); ?> - 
                                        <?php echo htmlspecialchars($student_data['year_level']); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-search me-2"></i>
                        <?php echo $student_data ? 'Change Student' : 'Find Student'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <?php if ($student_data): ?>
    <div class="col-lg-6">

        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Select Book to Borrow</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="mb-3 flex-grow-1">
                    <label class="form-label">Available Books 
                        <span class="badge bg-primary ms-1">
                            <?php echo $books_result ? $books_result->num_rows : 0; ?>
                        </span>
                    </label>
                    <form method="POST" id="borrowForm">
                        <input type="hidden" name="student_number" value="<?php echo $student_data['student_number']; ?>">
                        <select class="form-select form-select-lg" name="book_id" required>
                            <option value="">Choose a book to borrow...</option>
                            <?php 
                            if ($books_result) {
                                $books_result->data_seek(0);
                                while ($book = $books_result->fetch_assoc()): 
                            ?>
                            <option value="<?php echo $book['book_id']; ?>">
                                <?php echo htmlspecialchars($book['title']) . ' - ' . htmlspecialchars($book['author']); ?> 
                                <span class="text-muted">(<?php echo $book['available_copies']; ?> avail.)</span>
                            </option>
                            <?php 
                                endwhile; 
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="borrow_book" class="btn btn-success w-100 btn-lg">
                        <i class="fas fa-hand-holding me-2"></i>
                        <strong>Borrow Book Now</strong>
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function()
 {
    $('#studentForm').on('submit', function(e) 
    {
        var studentNumber = $('input[name="student_number"]').val().trim();
        if (!/^\d{4}-\d{4}$/.test(studentNumber)) 
            {
            e.preventDefault();
            alert('Please enter student number in format: 0324-0000');
            $('input[name="student_number"]').focus();
            return false;
        }
    });


    $('#borrowForm select[name="book_id"]').on('change', function() 
    {
        if ($(this).val()) 
            {
            $('#borrowForm').submit();
        }
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