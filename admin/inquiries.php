<?php
// admin/inquiries.php - Manage all inquiries
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Manage Inquiries';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Filters
$status_filter = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Build query
$where = [];
$params = [];

if ($status_filter && $status_filter !== 'all') {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

if ($search) {
    $where[] = "(name LIKE ? OR email LIKE ? OR message LIKE ? OR phone LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$where_clause = $where ? "WHERE " . implode(" AND ", $where) : "";

// Get total count
$count_sql = "SELECT COUNT(*) FROM inquiries $where_clause";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_inquiries = $count_stmt->fetchColumn();
$total_pages = ceil($total_inquiries / $limit);

// Get inquiries
$sql = "SELECT * FROM inquiries $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?";
$all_params = array_merge($params, [$limit, $offset]);
$stmt = $pdo->prepare($sql);
$stmt->execute($all_params);
$inquiries = $stmt->fetchAll();

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $selected_ids = $_POST['selected'] ?? [];
    
    if (!empty($selected_ids)) {
        $ids = implode(',', array_map('intval', $selected_ids));
        
        switch ($_POST['action']) {
            case 'mark_read':
                $pdo->exec("UPDATE inquiries SET status = 'read' WHERE id IN ($ids)");
                $_SESSION['flash_message'] = "Selected inquiries marked as read";
                break;
                
            case 'mark_spam':
                $pdo->exec("UPDATE inquiries SET status = 'spam' WHERE id IN ($ids)");
                $_SESSION['flash_message'] = "Selected inquiries marked as spam";
                break;
                
            case 'delete':
                $pdo->exec("DELETE FROM inquiries WHERE id IN ($ids)");
                $_SESSION['flash_message'] = "Selected inquiries deleted";
                break;
        }
        
        header("Location: inquiries.php");
        exit();
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="inquiries-container">
    <div class="page-header">
        <h1><i class="fas fa-inbox"></i> Manage Inquiries</h1>
        <div class="header-actions">
            <a href="export.php?type=inquiries" class="btn-secondary">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-bar">
        <form method="GET" action="" class="filters-form">
            <div class="filter-group">
                <select name="status" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="new" <?php echo $status_filter == 'new' ? 'selected' : ''; ?>>New</option>
                    <option value="read" <?php echo $status_filter == 'read' ? 'selected' : ''; ?>>Read</option>
                    <option value="replied" <?php echo $status_filter == 'replied' ? 'selected' : ''; ?>>Replied</option>
                    <option value="spam" <?php echo $status_filter == 'spam' ? 'selected' : ''; ?>>Spam</option>
                </select>
            </div>
            
            <div class="filter-group search-group">
                <input type="text" 
                       name="search" 
                       placeholder="Search inquiries..." 
                       value="<?php echo htmlspecialchars($search); ?>"
                       class="search-input">
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <a href="inquiries.php" class="btn-clear">Clear Filters</a>
        </form>
        
        <div class="bulk-actions">
            <select id="bulkAction" class="filter-select">
                <option value="">Bulk Actions</option>
                <option value="mark_read">Mark as Read</option>
                <option value="mark_spam">Mark as Spam</option>
                <option value="delete">Delete</option>
            </select>
            <button onclick="executeBulkAction()" class="btn-apply">Apply</button>
        </div>
    </div>
    
    <!-- Results summary -->
    <div class="results-info">
        Showing <?php echo count($inquiries); ?> of <?php echo $total_inquiries; ?> inquiries
        <?php if ($total_pages > 1): ?>
        (Page <?php echo $page; ?> of <?php echo $total_pages; ?>)
        <?php endif; ?>
    </div>
    
    <!-- Inquiries Table -->
    <form method="POST" id="bulkForm">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="30">
                            <input type="checkbox" id="selectAll" onclick="toggleAll(this)">
                        </th>
                        <th>ID</th>
                        <th>Name / Contact</th>
                        <th>Service</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>IP</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inquiry): ?>
                    <tr class="<?php echo $inquiry['status'] == 'new' ? 'row-new' : ''; ?>">
                        <td>
                            <input type="checkbox" name="selected[]" value="<?php echo $inquiry['id']; ?>" class="row-checkbox">
                        </td>
                        <td>#<?php echo $inquiry['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong>
                            <br><small><?php echo htmlspecialchars($inquiry['email']); ?></small>
                            <?php if ($inquiry['phone']): ?>
                            <br><small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($inquiry['phone']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="service-badge">
                                <?php echo htmlspecialchars($inquiry['service'] ?: 'General'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="message-preview">
                                <?php echo htmlspecialchars(substr($inquiry['message'], 0, 100)); ?>
                                <?php if (strlen($inquiry['message']) > 100): ?>...<?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $status_colors = [
                                'new' => 'warning',
                                'read' => 'info',
                                'replied' => 'success',
                                'spam' => 'danger'
                            ];
                            $color = $status_colors[$inquiry['status']] ?? 'secondary';
                            ?>
                            <span class="badge badge-<?php echo $color; ?>">
                                <?php echo ucfirst($inquiry['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo date('d M Y', strtotime($inquiry['created_at'])); ?>
                            <br><small><?php echo date('h:i A', strtotime($inquiry['created_at'])); ?></small>
                        </td>
                        <td>
                            <small><?php echo $inquiry['ip_address'] ?: '-'; ?></small>
                        </td>
                        <td class="actions">
                            <a href="inquiry-view.php?id=<?php echo $inquiry['id']; ?>" class="btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="mailto:<?php echo $inquiry['email']; ?>" class="btn-icon" title="Reply">
                                <i class="fas fa-reply"></i>
                            </a>
                            <a href="tel:<?php echo $inquiry['phone']; ?>" class="btn-icon" title="Call">
                                <i class="fas fa-phone"></i>
                            </a>
                            <a href="#" onclick="deleteInquiry(<?php echo $inquiry['id']; ?>)" class="btn-icon" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($inquiries)): ?>
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x"></i>
                                <h3>No inquiries found</h3>
                                <p>Try adjusting your filters or check back later.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page-1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
            <i class="fas fa-chevron-left"></i>
        </a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" 
           class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page+1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
            <i class="fas fa-chevron-right"></i>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.inquiries-container {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 24px;
    color: #333;
}

.page-header h1 i {
    color: #667eea;
    margin-right: 10px;
}

.btn-secondary {
    padding: 10px 20px;
    background: #f0f0f0;
    color: #333;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: #667eea;
    color: white;
}

.filters-bar {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filters-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-select {
    padding: 8px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    min-width: 150px;
}

.search-group {
    display: flex;
    align-items: center;
}

.search-input {
    padding: 8px 15px;
    border: 1px solid #ddd;
    border-radius: 6px 0 0 6px;
    font-size: 14px;
    width: 250px;
}

.btn-search {
    padding: 8px 15px;
    background: #667eea;
    border: none;
    border-radius: 0 6px 6px 0;
    color: white;
    cursor: pointer;
}

.btn-clear {
    padding: 8px 15px;
    color: #666;
    text-decoration: none;
}

.bulk-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-apply {
    padding: 8px 20px;
    background: #667eea;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}

.results-info {
    margin-bottom: 15px;
    color: #666;
    font-size: 14px;
}

.row-new {
    background: #fff3cd;
}

.message-preview {
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #666;
    font-size: 13px;
}

.service-badge {
    background: #f0f0f0;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
}

.actions {
    display: flex;
    gap: 5px;
}

.empty-state {
    text-align: center;
    padding: 50px;
    color: #999;
}

.empty-state i {
    margin-bottom: 15px;
    color: #ddd;
}

.empty-state h3 {
    margin-bottom: 10px;
    color: #666;
}

.pagination {
    display: flex;
    gap: 5px;
    justify-content: center;
    margin-top: 30px;
}

.page-link {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}

.page-link:hover {
    background: #f0f0f0;
}

.page-link.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .filters-form {
        flex-direction: column;
        width: 100%;
    }
    
    .search-input {
        width: 100%;
    }
    
    .bulk-actions {
        width: 100%;
    }
}
</style>

<script>
function toggleAll(source) {
    const checkboxes = document.getElementsByClassName('row-checkbox');
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

function executeBulkAction() {
    const action = document.getElementById('bulkAction').value;
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    const selected = document.querySelectorAll('.row-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select at least one inquiry');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete selected inquiries?')) {
            return;
        }
    }
    
    const form = document.getElementById('bulkForm');
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);
    
    form.submit();
}

function deleteInquiry(id) {
    if (confirm('Are you sure you want to delete this inquiry?')) {
        window.location.href = 'inquiry-delete.php?id=' + id;
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
