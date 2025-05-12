<?php
include 'includes/db.php';
session_start();

if ($_SESSION['role'] !== 'instructor') {
    header("Location: dashboard.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<style>
    :root {
        --primary: #4e73df;
        --primary-light: #7a9ef8;
        --secondary: #858796;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --light: #f8f9fc;
        --dark: #5a5c69;
        --gray-100: #f8f9fc;
        --gray-200: #eaecf4;
        --gray-300: #dddfeb;
        --gray-600: #b7b9cc;
    }
    
    body {
        background-color: #f8f9fc;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
    }
    
    .upload-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 15px;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.3rem 1.5rem rgba(58, 59, 69, 0.15);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .card:hover {
        box-shadow: 0 0.8rem 2.5rem rgba(58, 59, 69, 0.2);
        transform: translateY(-3px);
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--primary) 0%, #224abe 100%);
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
    }
    
    .card-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }
    
    .card-header h2 {
        font-weight: 800;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        position: relative;
    }
    
    .section-title {
        color: var(--dark);
        font-weight: 700;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--info));
        border-radius: 3px;
    }
    
    .form-control, .form-control-file, .form-select {
        border: 1px solid var(--gray-300);
        border-radius: 0.375rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
        background-color: var(--gray-100);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.15);
        background-color: white;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .form-label i {
        margin-right: 8px;
        color: var(--primary);
    }
    
    .btn {
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .btn::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }
    
    .btn:focus::after, .btn:hover::after {
        animation: ripple 1s ease-out;
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        padding: 0.75rem 2.5rem;
        box-shadow: 0 4px 6px rgba(78, 115, 223, 0.2);
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(78, 115, 223, 0.3);
    }
    
    .btn-outline-secondary {
        border-width: 2px;
    }
    
    .btn-outline-secondary:hover {
        background-color: var(--gray-200);
    }
    
    .assignment-item, .quiz-item {
        border-left: 4px solid var(--info);
        transition: all 0.3s;
        position: relative;
    }
    
    .assignment-item:hover, .quiz-item:hover {
        border-left-color: var(--primary);
        transform: translateX(5px);
    }
    
    .assignment-item::before, .quiz-item::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.03) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
    }
    
    .remove-item {
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s;
    }
    
    .remove-item:hover {
        transform: rotate(90deg);
    }
    
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-top: 0.15em;
    }
    
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .form-check-label {
        font-weight: 500;
    }
    
    /* Floating animation for form sections */
    @keyframes floatIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .form-section {
        animation: floatIn 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
    
    /* Tooltip styles */
    .tooltip-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        background-color: var(--gray-600);
        color: white;
        border-radius: 50%;
        font-size: 12px;
        margin-left: 8px;
        cursor: help;
        position: relative;
    }
    
    .tooltip-icon:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--dark);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        margin-bottom: 5px;
        z-index: 100;
    }
    
    /* Progress indicator for form */
    .progress-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .progress-step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--gray-200);
        color: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin: 0 10px;
        position: relative;
        transition: all 0.3s;
    }
    
    .progress-step.active {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }
    
    .progress-step.completed {
        background-color: var(--success);
        color: white;
    }
    
    .progress-step::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 100%;
        width: 20px;
        height: 2px;
        background-color: var(--gray-300);
    }
    
    .progress-step:last-child::after {
        display: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .card-header {
            padding: 1.5rem;
        }
        
        .btn {
            padding: 0.6rem 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .card-header h2 {
            font-size: 1.5rem;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
        
        .progress-step {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="upload-container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center">Create New Course</h2>
        </div>
        <div class="card-body p-4 p-md-5">
            <!-- Progress Indicator -->
            <div class="progress-indicator">
                <div class="progress-step active">1</div>
                <div class="progress-step">2</div>
                <div class="progress-step">3</div>
                <div class="progress-step">4</div>
            </div>
            
            <form action="process/upload_course.php" method="POST" enctype="multipart/form-data">
                <!-- Basic Course Information -->
                <div class="form-section mb-5">
                    <h4 class="section-title">
                        <i class="fas fa-info-circle"></i> Course Information
                    </h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i> Course Title*
                                </label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter course title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="instructor" class="form-label">
                                    <i class="fas fa-chalkboard-teacher"></i> Instructor
                                </label>
                                <input type="text" class="form-control" id="instructor" value="<?php echo $_SESSION['username']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Course Description*
                            <span class="tooltip-icon" data-tooltip="Provide detailed course overview">?</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Detailed course description" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-4">
                                <label for="youtube_url" class="form-label">
                                    <i class="fab fa-youtube"></i> YouTube Playlist URL
                                </label>
                                <input type="url" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/playlist?list=...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="thumbnail" class="form-label">
                                    <i class="fas fa-image"></i> Course Thumbnail
                                </label>
                                <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
                                <small class="text-muted">Recommended: 1280×720px</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignments Section -->
                <div class="form-section mb-5">
                    <h4 class="section-title">
                        <i class="fas fa-tasks"></i> Assignments
                    </h4>
                    <div id="assignment-container">
                        <div class="assignment-item card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fas fa-pencil-alt mr-2"></i>Assignment</h5>
                                    <button type="button" class="btn btn-sm btn-danger remove-item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Assignment Title*</label>
                                    <input type="text" class="form-control" name="assignment_title[]" placeholder="Assignment title" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Instructions*</label>
                                    <textarea class="form-control" name="instructions[]" rows="3" placeholder="Detailed instructions" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Deadline*</label>
                                            <input type="datetime-local" class="form-control" name="deadline[]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Maximum Points*</label>
                                            <input type="number" class="form-control" name="max_points[]" min="1" value="100" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-assignment">
                        <i class="fas fa-plus mr-2"></i>Add Another Assignment
                    </button>
                </div>

                <!-- Quizzes Section -->
                <div class="form-section mb-5">
                    <h4 class="section-title">
                        <i class="fas fa-question-circle"></i> Quizzes
                    </h4>
                    <div id="quiz-container">
                        <div class="quiz-item card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fas fa-question mr-2"></i>Question</h5>
                                    <button type="button" class="btn btn-sm btn-danger remove-item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Question*</label>
                                    <input type="text" class="form-control" name="question[]" placeholder="Enter question" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Option A*</label>
                                            <input type="text" class="form-control" name="option_a[]" placeholder="Option A" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Option B*</label>
                                            <input type="text" class="form-control" name="option_b[]" placeholder="Option B" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Option C*</label>
                                            <input type="text" class="form-control" name="option_c[]" placeholder="Option C" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Option D*</label>
                                            <input type="text" class="form-control" name="option_d[]" placeholder="Option D" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Correct Option*</label>
                                            <select class="form-control" name="correct_option[]" required>
                                                <option value="A">Option A</option>
                                                <option value="B">Option B</option>
                                                <option value="C">Option C</option>
                                                <option value="D">Option D</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Points*</label>
                                            <input type="number" class="form-control" name="question_points[]" min="1" value="10" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-quiz">
                        <i class="fas fa-plus mr-2"></i>Add Another Question
                    </button>
                </div>

                <!-- Course Settings -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-cog"></i> Course Settings
                    </h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label">Course Category*</label>
                                <select class="form-control" name="category" required>
                                    <option value="">Select category</option>
                                    <option value="Programming">Programming</option>
                                    <option value="Data Analytics">Data Analytics</option>
                                    <option value="Python">Python</option>
                                    <option value="Digital Marketing">Digital Marketing </option>
                                    <option value="UI/UX">UI/UX</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label">Course Difficulty*</label>
                                <select class="form-control" name="difficulty" required>
                                    <option value="Beginner">Beginner</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_premium" name="is_premium">
                        <label class="form-check-label" for="is_premium">
                            <i class="fas fa-crown mr-2"></i>Premium Course (Requires payment)
                        </label>
                    </div>
                    
                    <div class="row" id="price-field" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label">Course Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="price" min="0" step="0.01" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label">Discount (%)</label>
                                <div class="input-group">
                                    <span class="input-group-text">%</span>
                                    <input type="number" class="form-control" name="discount" min="0" max="100" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 mr-3">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>Publish Course
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg px-5 py-3">
                        <i class="fas fa-undo mr-2"></i>Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add Font Awesome
document.addEventListener('DOMContentLoaded', function() {
    const fa = document.createElement('link');
    fa.rel = 'stylesheet';
    fa.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
    document.head.appendChild(fa);
    
    // Toggle price field visibility
    document.getElementById('is_premium').addEventListener('change', function() {
        document.getElementById('price-field').style.display = this.checked ? 'block' : 'none';
    });
});

// Add dynamic assignment fields
document.getElementById('add-assignment').addEventListener('click', function() {
    const container = document.getElementById('assignment-container');
    const newItem = document.createElement('div');
    newItem.className = 'assignment-item card mb-4';
    newItem.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-pencil-alt mr-2"></i>New Assignment</h5>
                <button type="button" class="btn btn-sm btn-danger remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Assignment Title*</label>
                <input type="text" class="form-control" name="assignment_title[]" placeholder="Assignment title" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Instructions*</label>
                <textarea class="form-control" name="instructions[]" rows="3" placeholder="Detailed instructions" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Deadline*</label>
                        <input type="datetime-local" class="form-control" name="deadline[]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Maximum Points*</label>
                        <input type="number" class="form-control" name="max_points[]" min="1" value="100" required>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    
    // Animate the new item
    newItem.style.opacity = '0';
    setTimeout(() => {
        newItem.style.transition = 'opacity 0.3s ease';
        newItem.style.opacity = '1';
    }, 10);
});

// Add dynamic quiz fields
document.getElementById('add-quiz').addEventListener('click', function() {
    const container = document.getElementById('quiz-container');
    const newItem = document.createElement('div');
    newItem.className = 'quiz-item card mb-4';
    newItem.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-question mr-2"></i>New Question</h5>
                <button type="button" class="btn btn-sm btn-danger remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Question*</label>
                <input type="text" class="form-control" name="question[]" placeholder="Enter question" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Option A*</label>
                        <input type="text" class="form-control" name="option_a[]" placeholder="Option A" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Option B*</label>
                        <input type="text" class="form-control" name="option_b[]" placeholder="Option B" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Option C*</label>
                        <input type="text" class="form-control" name="option_c[]" placeholder="Option C" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Option D*</label>
                        <input type="text" class="form-control" name="option_d[]" placeholder="Option D" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Correct Option*</label>
                        <select class="form-control" name="correct_option[]" required>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Points*</label>
                        <input type="number" class="form-control" name="question_points[]" min="1" value="10" required>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    
    // Animate the new item
    newItem.style.opacity = '0';
    setTimeout(() => {
        newItem.style.transition = 'opacity 0.3s ease';
        newItem.style.opacity = '1';
    }, 10);
});

// Remove dynamic fields
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') {
        const item = e.target.closest('.assignment-item, .quiz-item');
        item.style.transition = 'all 0.3s ease';
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        setTimeout(() => item.remove(), 300);
    }
});

// Progress indicator logic
const formSections = document.querySelectorAll('.form-section');
const progressSteps = document.querySelectorAll('.progress-step');

function updateProgressIndicator() {
    const scrollPosition = window.scrollY + (window.innerHeight / 2);
    
    formSections.forEach((section, index) => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        
        if (scrollPosition >= sectionTop && scrollPosition <= sectionTop + sectionHeight) {
            progressSteps.forEach((step, stepIndex) => {
                if (stepIndex === index) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                } else if (stepIndex < index) {
                    step.classList.remove('active');
                    step.classList.add('completed');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });
        }
    });
}

window.addEventListener('scroll', updateProgressIndicator);
window.addEventListener('load', updateProgressIndicator);
</script>

<?php include 'includes/footer.php'; ?>