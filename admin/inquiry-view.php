<?php
// admin/inquiry-view.php - View single inquiry
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get inquiry details
$stmt = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
$stmt->execute([$id]);
$inquiry = $stmt->fetch();

if (!$inquiry) {
    header("Location: inquiries.php");
    exit();
}

// Mark as read if new
if ($inquiry['status'] == 'new') {
    $update = $pdo->prepare("UPDATE inquiries SET status = 'read' WHERE id = ?");
    $update->execute([$id]);
}

include __DIR__ . '/includes/header.php';
?>

<div class="inquiry-view-container">
    <div class="view-header">
        <h1><i class="fas fa-envelope-open"></i> Inquiry #<?php echo $id; ?></h1>
        <div class="header-actions">
            <a href="inquiries.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="mailto:<?php echo $inquiry['email']; ?>" class="btn-primary">
                <i class="fas fa-reply"></i> Reply via Email
            </a>
        </div>
    </div>
    
    <div class="inquiry-detail-grid">
        <!-- Main Inquiry Details -->
        <div class="inquiry-main">
            <div class="detail-card">
                <div class="card-header">
                    <h3>Inquiry Details</h3>
                    <span class="status-badge status-<?php echo $inquiry['status']; ?>">
                        <?php echo ucfirst($inquiry['status']); ?>
                    </span>
                </div>
                
                <div class="detail-content">
                    <div class="detail-row">
                        <div class="detail-label">From:</div>
                        <div class="detail-value">
                            <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong>
                            <br>
                            <a href="mailto:<?php echo $inquiry['email']; ?>"><?php echo $inquiry['email']; ?></a>
                        </div>
                    </div>
                    
                    <?php if ($inquiry['phone']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">
                            <a href="tel:<?php echo $inquiry['phone']; ?>"><?php echo $inquiry['phone']; ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-row">
                        <div class="detail-label">Service:</div>
                        <div class="detail-value">
                            <span class="service-tag">
                                <?php echo htmlspecialchars($inquiry['service'] ?: 'General Inquiry'); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Received:</div>
                        <div class="detail-value">
                            <?php echo date('F j, Y \a\t g:i A', strtotime($inquiry['created_at'])); ?>
                        </div>
                    </div>
                    
                    <div class="detail-row message-row">
                        <div class="detail-label">Message:</div>
                        <div class="detail-value message-content">
                            <?php echo nl2br(htmlspecialchars($inquiry['message'])); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Reply -->
            <div class="detail-card">
                <div class="card-header">
                    <h3>Quick Reply</h3>
                </div>
                
                <div class="reply-form">
                    <form action="send-reply.php" method="POST" id="replyForm">
                        <input type="hidden" name="inquiry_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="to_email" value="<?php echo $inquiry['email']; ?>">
                        <input type="hidden" name="to_name" value="<?php echo htmlspecialchars($inquiry['name']); ?>">
                        
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" 
                                   name="subject" 
                                   value="Re: Your inquiry regarding <?php echo $inquiry['service'] ?: 'our services'; ?>"
                                   class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" 
                                      rows="6" 
                                      class="form-control" 
                                      required><?php echo "Dear " . $inquiry['name'] . ",\n\nThank you for contacting Indiz Software.\n\n"; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                            <button type="button" class="btn-secondary" onclick="markAsReplied(<?php echo $id; ?>)">
                                <i class="fas fa-check"></i> Mark as Replied
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="inquiry-sidebar">
            <div class="detail-card">
                <div class="card-header">
                    <h3>Actions</h3>
                </div>
                
                <div class="action-buttons">
                    <button onclick="updateStatus(<?php echo $id; ?>, 'read')" class="action-btn">
                        <i class="fas fa-check-circle"></i> Mark as Read
                    </button>
                    
                    <button onclick="updateStatus(<?php echo $id; ?>, 'spam')" class="action-btn">
                        <i class="fas fa-ban"></i> Mark as Spam
                    </button>
                    
                    <a href="tel:<?php echo $inquiry['phone']; ?>" class="action-btn">
                        <i class="fas fa-phone"></i> Call Now
                    </a>
                    
                    <a href="https://wa.me/91<?php echo $inquiry['phone']; ?>" target="_blank" class="action-btn">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    
                    <button onclick="deleteInquiry(<?php echo $id; ?>)" class="action-btn delete-btn">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            
            <div class="detail-card">
                <div class="card-header">
                    <h3>Technical Info</h3>
                </div>
                
                <div class="tech-info">
                    <div class="info-item">
                        <span class="info-label">IP Address:</span>
                        <span class="info-value"><?php echo $inquiry['ip_address'] ?: 'N/A'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">User Agent:</span>
                        <span class="info-value small"><?php echo htmlspecialchars($inquiry['user_agent'] ?: 'N/A'); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Email Sent:</span>
                        <span class="info-value">
                            <?php echo $inquiry['email_sent'] ? 'Yes' : 'No'; ?>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">
                            <?php echo date('Y-m-d H:i:s', strtotime($inquiry['updated_at'])); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="detail-card">
                <div class="card-header">
                    <h3>Quick Templates</h3>
                </div>
                
                <div class="template-list">
                    <button onclick="insertTemplate('thankyou')" class="template-btn">
                        <i class="fas fa-file-alt"></i> Thank You
                    </button>
                    <button onclick="insertTemplate('meeting')" class="template-btn">
                        <i class="fas fa-calendar"></i> Schedule Meeting
                    </button>
                    <button onclick="insertTemplate('quote')" class="template-btn">
                        <i class="fas fa-calculator"></i> Send Quote
                    </button>
                    <button onclick="insertTemplate('moreinfo')" class="template-btn">
                        <i class="fas fa-question-circle"></i> Request Info
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.inquiry-view-container {
    padding: 20px;
}

.view-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.view-header h1 {
    font-size: 24px;
    color: #333;
}

.view-header h1 i {
    color: #667eea;
    margin-right: 10px;
}

.inquiry-detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.detail-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 16px;
    color: #333;
}

.status-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-new {
    background: #fff3cd;
    color: #856404;
}

.status-read {
    background: #d1ecf1;
    color: #0c5460;
}

.status-replied {
    background: #d4edda;
    color: #155724;
}

.status-spam {
    background: #f8d7da;
    color: #721c24;
}

.detail-content {
    padding: 20px;
}

.detail-row {
    display: grid;
    grid-template-columns: 100px 1fr;
    gap: 15px;
    margin-bottom: 15px;
}

.detail-label {
    color: #666;
    font-weight: 500;
    font-size: 14px;
}

.detail-value {
    color: #333;
}

.message-row {
    align-items: start;
}

.message-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    line-height: 1.6;
    white-space: pre-wrap;
}

.service-tag {
    background: #eef2ff;
    color: #667eea;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 12px;
}

.reply-form {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #666;
    font-weight: 500;
    font-size: 13px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}

.form-actions {
    display: flex;
    gap: 10px;
}

.action-buttons {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-btn {
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 8px;
    color: #333;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    cursor: pointer;
    width: 100%;
}

.action-btn:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.action-btn i {
    width: 20px;
}

.delete-btn:hover {
    background: #ef4444;
    border-color: #ef4444;
}

.tech-info {
    padding: 15px;
}

.info-item {
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.info-label {
    display: block;
    color: #666;
    font-size: 12px;
    margin-bottom: 3px;
}

.info-value {
    font-size: 13px;
    color: #333;
}

.info-value.small {
    font-size: 11px;
    word-break: break-all;
}

.template-list {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.template-btn {
    padding: 8px;
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 6px;
    color: #333;
    cursor: pointer;
    text-align: left;
    font-size: 12px;
}

.template-btn:hover {
    background: #667eea;
    color: white;
}

@media (max-width: 768px) {
    .inquiry-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-row {
        grid-template-columns: 1fr;
        gap: 5px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
function updateStatus(id, status) {
    if (confirm('Mark this inquiry as ' + status + '?')) {
        window.location.href = 'update-status.php?id=' + id + '&status=' + status;
    }
}

function deleteInquiry(id) {
    if (confirm('Are you sure you want to delete this inquiry?')) {
        window.location.href = 'inquiry-delete.php?id=' + id;
    }
}

function markAsReplied(id) {
    if (confirm('Mark this inquiry as replied?')) {
        window.location.href = 'update-status.php?id=' + id + '&status=replied';
    }
}

function insertTemplate(type) {
    const messageBox = document.querySelector('textarea[name="message"]');
    const templates = {
        thankyou: "Thank you for contacting Indiz Software. We appreciate your interest in our services.\n\nOur team will review your inquiry and get back to you within 24 hours.\n\nBest regards,\n" + document.querySelector('meta[name="admin-name"]')?.content || 'Team Indiz',
        
        meeting: "Thank you for reaching out. I'd like to schedule a meeting to discuss your requirements in detail.\n\nWould you be available for a video call this week? Please let me know your preferred time.\n\nRegards",
        
        quote: "Thank you for your interest in our services. Based on your requirements, I'll prepare a detailed quote for you.\n\nTo provide an accurate estimate, could you please share more details about:\n- Number of users/students\n- Specific features needed\n- Timeline expectations\n\nRegards",
        
        moreinfo: "Thank you for contacting us. To better understand your requirements, could you please provide more information about:\n\n1. Your current process\n2. Specific challenges you're facing\n3. Expected timeline\n\nThis will help us provide a more tailored solution."
    };
    
    if (templates[type] && messageBox) {
        messageBox.value = templates[type];
    }
}

// Auto-resize textarea
document.querySelector('textarea')?.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
