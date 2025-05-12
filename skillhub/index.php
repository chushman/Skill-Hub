<?php 
// Start session at the very beginning
session_start();
include 'includes/header.php'; 
?>

<style>
    /* Modified hero-section to work with carousel */
    .hero-section {
        height: 90vh;
        color: white;
    }
    
    /* Ensure carousel images cover the full height */
    .carousel, .carousel-inner, .carousel-item {
        height: 100%;
    }
    
    .carousel-item img {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }
    
    .hero-content {
        max-width: 800px;
        padding: 0 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    
    .hero-content h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .hero-content p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        line-height: 1.6;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    .btn-hero {
        background-color: #65251a;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-hero:hover {
        background-color: #502c2c;
        transform: translateY(-3px);
    }
    
    /* Make carousel captions visible */
    .carousel-caption {
        display: block !important;
        bottom: initial;
        top: 50%;
        transform: translateY(-50%);
    }
</style>

<section class="hero-section">
    <div id="carouselExampleDark" class="carousel carousel-dark slide h-100" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100" data-bs-interval="10000">
                <img src="assets/images/slider1.png" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h1>Welcome to Skill Hub</h1>
                    <p>"Learn. Grow. Lead." Skill Hub is your gateway to mastering new skills with our comprehensive courses. 
                       Join our community of learners today and unlock your full potential.</p>
                    
                    <?php if(!isset($_SESSION['loggedin'])): ?>
                        <a href="register.php" class="btn-hero">Get Started</a>
                    <?php else: ?>
                        <a href="courses.php" class="btn-hero">Browse Courses</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="carousel-item h-100" data-bs-interval="2000">
                <img src="assets/images/slider2.png" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h1>Welcome to Skill Hub</h1>
                    <p>"Learn. Grow. Lead." Skill Hub is your gateway to mastering new skills with our comprehensive courses. 
                       Join our community of learners today and unlock your full potential.</p>
                    
                    <?php if(!isset($_SESSION['loggedin'])): ?>
                        <a href="register.php" class="btn-hero">Get Started</a>
                    <?php else: ?>
                        <a href="courses.php" class="btn-hero">Browse Courses</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="carousel-item h-100">
                <img src="assets/images/slider3.png" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h1>Welcome to Skill Hub</h1>
                    <p>"Learn. Grow. Lead." Skill Hub is your gateway to mastering new skills with our comprehensive courses. 
                       Join our community of learners today and unlock your full potential.</p>
                    
                    <?php if(!isset($_SESSION['loggedin'])): ?>
                        <a href="register.php" class="btn-hero">Get Started</a>
                    <?php else: ?>
                        <a href="courses.php" class="btn-hero">Browse Courses</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>