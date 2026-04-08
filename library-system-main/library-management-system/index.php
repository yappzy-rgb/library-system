<?php
$page_title = "Books Management";
require_once 'config/config.php';

// Handle form submissions
$message = '';
$message_type = '';

if ($_POST) {
    if (isset($_POST['add_book'])) {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $category = trim($_POST['category']);
        $year_published = !empty($_POST['year_published']) ? (int)$_POST['year_published'] : null;
        $total_copies = (int)$_POST['total_copies'];
        
        // Validate input
        if (!empty($title) && !empty($author) && !empty($category) && $total_copies > 0) {
            if ($year_published && $year_published >= 1800 && $year_published <= (date('Y') + 1)) {
                // Insert with year (INTEGER binding)
                $stmt = $conn->prepare("INSERT INTO books (title, author, category, year_published, total_copies, available_copies) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiii", $title, $author, $category, $year_published, $total_copies, $total_copies);
            } else {
                // Insert without year
                $stmt = $conn->prepare("INSERT INTO books (title, author, category, total_copies, available_copies) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiii", $title, $author, $category, $total_copies, $total_copies);
            }
            
            if ($stmt->execute()) {
                $message = "✅ Book added successfully!";
                $message_type = "success";
             
                $_POST = array();
            } else {
                $message = "❌ Database Error: " . $stmt->error;
                $message_type = "danger";
            }
            $stmt->close();
        } else {
            $message = "⚠️ Please fill all required fields correctly!";
            $message_type = "warning";
        }
    }
    
    if (isset($_POST['delete_book'])) {
        $book_id = (int)$_POST['book_id'];
        // Check if book has active borrows
        $check_borrow = $conn->prepare("SELECT COUNT(*) as count FROM borrow_records WHERE book_id = ? AND status = 'Borrowed'");
        $check_borrow->bind_param("i", $book_id);
        $check_borrow->execute();
        $borrow_count = $check_borrow->get_result()->fetch_assoc()['count'];
        
        if ($borrow_count > 0) {
            $message = "Cannot delete book with active borrows!";
            $message_type = "warning";
        } else {
            $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            if ($stmt->execute()) {
                $message = "🗑️ Book deleted successfully!";
                $message_type = "success";
            } else {
                $message = "❌ Error deleting book: " . $stmt->error;
                $message_type = "danger";
            }
        }
    }
}

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $books_query = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR category LIKE ?";
    $stmt = $conn->prepare($books_query);
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
} else {
    $books_query = "SELECT * FROM books";
    $stmt = $conn->prepare($books_query);
}
$stmt->execute();
$books_result = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>
\
<?php if ($message): ?>
<div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
    <i class="fas <?php echo $message_type == 'success' ? 'fa-check-circle' : ($message_type == 'danger' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle'); ?> me-2"></i>
    <?php echo htmlspecialchars($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-primary mb-0"><i class="fas fa-book me-2"></i>Books Management</h2>
            <span class="badge bg-primary fs-6"><?php echo $books_result->num_rows; ?> Books</span>
        </div>
    </div>
</div>

<!-- FIXED Add Book Form -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Book</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Book Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required maxlength="200">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Author <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="author" value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>" required maxlength="100">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="category" value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category']) : ''; ?>" required maxlength="50">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Year Published</label>
                    <input type="number" class="form-control" name="year_published" min="1800" max="<?php echo date('Y')+1; ?>" step="1" value="<?php echo isset($_POST['year_published']) ? $_POST['year_published'] : date('Y'); ?>" placeholder="Optional">
                    <div class="form-text">Leave empty for no year</div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold">Total Copies <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="total_copies" min="1" max="100" value="<?php echo isset($_POST['total_copies']) ? $_POST['total_copies'] : '1'; ?>" required>
                </div>
            </div>
            <button type="submit" name="add_book" class="btn btn-primary btn-lg mt-3 px-4">
                <i class="fas fa-plus me-2"></i>Add Book
            </button>
        </form>
    </div>
</div>

<!-- Search -->
<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-3"><i class="fas fa-search me-2 text-primary"></i>Search Books</h5>
        <form method="GET" class="d-flex gap-2">
            <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Title, author, or category..." style="min-width: 300px;">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if (!empty($search)): ?>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
            <?php endif; ?>
        </form>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header bg-light border-0">
        <h5 class="mb-0 d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2 text-primary"></i>Books Inventory</span>
            <span class="badge bg-success"><?php echo $books_result->num_rows; ?> Total</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="60">#</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Year</th>
                        <th>Total</th>
                        <th>Available</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($books_result->num_rows > 0): ?>
                        <?php $counter = 1; while ($book = $books_result->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($book['category']); ?></span></td>
                            <td><?php echo $book['year_published'] ?: '<span class="text-muted">-</span>'; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $book['total_copies']; ?></span></td>
                            <td>
                                <span class="badge <?php echo $book['available_copies'] == 0 ? 'bg-danger' : 'bg-success'; ?>">
                                    <?php echo $book['available_copies']; ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-inline me-1">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                    <button type="submit" name="delete_book" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Delete book: <?php echo addslashes($book['title']); ?>?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-books fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No books found<?php echo $search ? " for '$search'" : ''; ?></h5>
                                <p class="text-muted">Add your first book above!</p>
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
