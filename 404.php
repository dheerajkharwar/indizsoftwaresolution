<?php
// 404.php - Page not found
$page_title = "Page Not Found";
require_once 'header.php';
?>

<section class="error-section">
    <div class="container">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1>Page Not Found</h1>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            
            <div class="error-actions">
                <a href="index.php" class="btn-primary">← Go to Homepage</a>
                <a href="contact.php" class="btn-outline">Contact Support</a>
            </div>
            
            <div class="helpful-links">
                <h3>Maybe these will help:</h3>
                <div class="links-grid">
                    <a href="services.php">Our Services</a>
                    <a href="about.php">About Us</a>
                    <a href="school-erp.php">School ERP</a>
                    <a href="inventory-pos.php">POS System</a>
                    <a href="ecommerce.php">E-commerce</a>
                    <a href="contact.php">Contact</a>
                </div>
            </div>
            
            <div class="search-box">
                <h3>Search our site:</h3>
                <form action="search.php" method="get">
                    <input type="text" name="q" placeholder="What are you looking for?">
                    <button type="submit" class="btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.error-section {
    min-height: 70vh;
    padding: 4rem 0;
    display: flex;
    align-items: center;
}

.error-content {
    max-width: 700px;
    margin: 0 auto;
    text-align: center;
}

.error-code {
    font-size: 8rem;
    font-weight: 800;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1;
    margin-bottom: 1rem;
}

.error-content h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.error-content p {
    font-size: 1.2rem;
    color: #475569;
    margin-bottom: 2rem;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin: 2rem 0;
}

.helpful-links {
    margin: 3rem 0;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 20px;
}

.links-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-top: 1.5rem;
}

.links-grid a {
    color: #2563eb;
    text-decoration: none;
    padding: 0.5rem;
    background: white;
    border-radius: 8px;
    transition: all 0.2s;
}

.links-grid a:hover {
    background: #2563eb;
    color: white;
}

.search-box {
    margin: 2rem 0;
}

.search-box form {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.search-box input {
    flex: 1;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .error-code {
        font-size: 5rem;
    }
    
    .error-actions {
        flex-direction: column;
    }
    
    .links-grid {
        grid-template-columns: 1fr;
    }
    
    .search-box form {
        flex-direction: column;
    }
}
</style>

<?php require_once 'footer.php'; ?>