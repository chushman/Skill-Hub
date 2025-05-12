<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<style>
    /* Footer Styles */
.footer {
    background: #22100d;
    color: white;
    padding: 70px 0 0;
    position: relative;
}

.footer-container {
    max-width: 1200px;
    margin: auto;
    padding: 0 20px;
}

.footer-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer-col {
    width: 25%;
    padding: 0 15px;
    margin-bottom: 30px;
}

.footer-col h4 {
    font-size: 18px;
    color: white;
    margin-bottom: 25px;
    font-weight: 600;
    position: relative;
}

.footer-col h4::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: -10px;
    background: var(--main-color);
    height: 2px;
    box-sizing: border-box;
    width: 50px;
}

.footer-col ul {
    list-style: none;
    padding-left: 0;
}

.footer-col ul li {
    margin-bottom: 15px;
}

.footer-col ul li a {
    color: #bbbbbb;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-col ul li a:hover {
    color: white;
    padding-left: 5px;
}

.social-links {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    width: 40px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: var(--main-color);
    transform: translateY(-3px);
}

.social-links a i {
    font-size: 20px;
}

.newsletter input {
    width: 100%;
    padding: 12px;
    border: none;
    outline: none;
    border-radius: 4px;
    margin-bottom: 10px;
}

.newsletter button {
    width: 100%;
    padding: 12px;
    background: var(--main-color);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.newsletter button:hover {
    background: #6e1f2f;
}

.copyright {
    text-align: center;
    padding: 20px 0;
    background: rgba(0, 0, 0, 0.2);
    color: #bbbbbb;
    font-size: 14px;
}

/* Responsive Footer */
@media (max-width: 768px) {
    .footer-col {
        width: 50%;
        margin-bottom: 30px;
    }
}

@media (max-width: 480px) {
    .footer-col {
        width: 100%;
    }
}
</style>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-row">
            <div class="footer-col">
                <h4>SkillHub</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Services</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Affiliate Program</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Get Help</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Courses</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Payment Options</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Categories</h4>
                <ul>
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">Data Science</a></li>
                    <li><a href="#">Mobile Development</a></li>
                    <li><a href="#">Design</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#"><i class="bx bxl-facebook"></i></a>
                    <a href="#"><i class="bx bxl-twitter"></i></a>
                    <a href="#"><i class="bx bxl-instagram"></i></a>
                    <a href="#"><i class="bx bxl-linkedin"></i></a>
                </div>
                <div class="newsletter">
                    <input type="email" placeholder="Your Email">
                    <button type="submit">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <p>&copy; 2025 SkillHub. All Rights Reserved</p>
    </div>
</footer>


