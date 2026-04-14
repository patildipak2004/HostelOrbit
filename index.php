<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSTELORBIT - Smart Hostel Management System</title>
    <link rel="stylesheet" href="assets/css/index.css">

</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h2>HOSTELORBIT</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#home" class="active"><span>🏠</span> Home</a></li>
            <li><a href="#about"><span>ℹ️</span> About</a></li>
            <li><a href="#facilities"><span>⚡</span> Facilities</a></li>
            <li><a href="#gallery"><span>🖼️</span> Gallery</a></li>
            <li><a href="#testimonials"><span>💬</span> Testimonials</a></li>
            <li><a href="#contact"><span>📞</span> Contact</a></li>
        </ul>
        <div class="sidebar-dropdown">
            <button class="dropbtn" id="loginBtn">
                <span>Login</span>
                <span>▼</span>
            </button>
            <div class="dropdown-content" id="loginDropdown">
                <a href="login.php?role=admin">Admin Login</a>
                <a href="login.php?role=student">Student Login</a>
                <a href="login.php?role=staff">Staff Login</a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <h1>Welcome to Saraswat Boarding Kolhapur</h1>
            <div class="topbar-actions">
                <a href="register.php" class="btn btn-primary">Apply Now</a>
            </div>
        </div>

        <!-- Hero Section -->
        <section class="hero" id="home">
            <h2>Smart, Clean & Secure Hostel Living</h2>
            <p>Experience premium student accommodation with world-class facilities and 24/7 support</p>
            <a href="register.php" class="btn btn-primary">Apply for Admission</a>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>11,000+</h3>
                        <p>Happy Students</p>
                    </div>
                    <div class="stat-card">
                        <h3>55</h3>
                        <p>Spacious Rooms</p>
                    </div>
                    <div class="stat-card">
                        <h3>24/7</h3>
                        <p>Security & WiFi</p>
                    </div>
                    <div class="stat-card">
                        <h3>111 Years</h3>
                        <p>Of Excellence</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="section" id="about">
            <div class="container">
                <div class="section-header">
                    <h2>About Shri. Saraswatibai Gaud Saraswat Brahman Vidyarthi Vasatigruh</h2>
                </div>

                <!-- Legacy Highlights -->
                <div class="about-highlights">
                    <div class="highlight-box">
                        <h4>Established</h4>
                        <p>May 20, 1915</p>
                    </div>
                    <div class="highlight-box">
                        <h4>110+ Years</h4>
                        <p>Of Continuous Service</p>
                    </div>
                    <div class="highlight-box">
                        <h4>Location</h4>
                        <p>Dasara Chowk, Kolhapur</p>
                    </div>
                    <div class="highlight-box">
                        <h4>Vision</h4>
                        <p>Rajarshi Chhatrapati Shahu Maharaj</p>
                    </div>
                </div>

                <!-- Historical Background -->
                <div class="about-content-block">
                    <h3>Historical Background</h3>
                    <p>Our hostel is rooted in a century-old legacy of social reform and educational excellence, established during the freedom struggle era of early 20th century India. Founded in 1915, our institution stands as a testament to the visionary ideals of <strong>Rajarshi Chhatrapati Shahu Maharaj</strong>, a legendary social reformer and the enlightened ruler of Kolhapur.</p>
                    
                    <h4>Founding Story</h4>
                    <ul class="timeline-list">
                        <li><strong>1911:</strong> Land allotted by Rajarshi Chhatrapati Shahu Maharaj to the Saraswat Community</li>
                        <li><strong>1913:</strong> Financial grant of Rs. 1,600 sanctioned for hostel formation</li>
                        <li><strong>1915:</strong> Rs. 10,000 donation from Shrimati Saraswatibai Ganesh Latkar enabled construction of a stone-structured building with 11 rooms</li>
                        <li><strong>May 20, 1915:</strong> Official inauguration of the hostel</li>
                    </ul>
                </div>

                <!-- Mission & Values -->
                <div class="about-content-block">
                    <h3>Our Mission & Values</h3>
                    <div class="mission-quote">
                        To provide quality education and accommodation to talented rural students from Maharashtra, Karnataka, and Goa, eliminating social deprivation and inspiring them to join the mainstream of educational flow.
                    </div>
                    
                    <h4>Core Values</h4>
                    <div class="values-grid">
                        <div class="value-card">
                            <strong>Social Inclusion</strong>
                            <p>Eliminating social deprivation through accessible education</p>
                        </div>
                        <div class="value-card">
                            <strong>Rural Development</strong>
                            <p>Bringing exceptional rural talent into the mainstream</p>
                        </div>
                        <div class="value-card">
                            <strong>Quality Education</strong>
                            <p>Providing comprehensive support for academic excellence</p>
                        </div>
                        <div class="value-card">
                            <strong>Scholarship Support</strong>
                            <p>Disbursing scholarships to deserving students</p>
                        </div>
                        <div class="value-card">
                            <strong>Sustainable Growth</strong>
                            <p>Balancing financial stability with student development</p>
                        </div>
                        <div class="value-card">
                            <strong>Community Service</strong>
                            <p>Building inclusive, supportive environments</p>
                        </div>
                    </div>
                </div>

                <!-- Key Milestones -->
                <div class="about-content-block">
                    <h3>Key Milestones</h3>
                    <div class="milestones-timeline">
                        <div class="milestone-item">
                            <strong>1911</strong>
                            <p>Land allotment to Saraswat Community by Rajarshi Chhatrapati Shahu Maharaj</p>
                        </div>
                        <div class="milestone-item">
                            <strong>1913</strong>
                            <p>Rs. 1,600 grant sanctioned for hostel formation</p>
                        </div>
                        <div class="milestone-item milestone-highlight">
                            <strong>May 20, 1915</strong>
                            <p>Official inauguration of the hostel</p>
                        </div>
                        <div class="milestone-item">
                            <strong>1964-1999</strong>
                            <p>Major expansion and modernization under K. D. Kamat's leadership (35 years)</p>
                        </div>
                        <div class="milestone-item">
                            <strong>Modern Era</strong>
                            <p>Development of additional facilities - library, reading room, study spaces</p>
                        </div>
                        <div class="milestone-item">
                            <strong>2015</strong>
                            <p>Celebration of 100 years of service</p>
                        </div>
                    </div>
                </div>

                <!-- Leadership -->
                <div class="about-content-block">
                    <h3>Leadership & Contributors</h3>
                    <h4>Prominent Leaders</h4>
                    <div class="leaders-grid">
                        <div class="leader-card">
                            <div class="leader-name">Late K. D. Kamat</div>
                            <div class="leader-period">1964-1999 (35 years)</div>
                            <p>Major expansion, modernization, and financial independence initiatives</p>
                        </div>
                        <div class="leader-card">
                            <div class="leader-name">Late Manmohan Ganesh Latkar</div>
                            <div class="leader-period">1921-2014</div>
                            <p>Extended service spanning 53+ years</p>
                        </div>
                        <div class="leader-card">
                            <div class="leader-name">Late Prof. V. A. Desai</div>
                            <div class="leader-period">Early-Mid Period</div>
                            <p>Educational guidance and organizational development</p>
                        </div>
                        <div class="leader-card">
                            <div class="leader-name">Late Krishnarao Dadasaheb Kamat</div>
                            <div class="leader-period">Mid Period</div>
                            <p>Organization leadership and growth</p>
                        </div>
                    </div>
                </div>

                <!-- Dedication -->
                <div class="dedication-box">
                    <p>"We dedicate our journey and continued efforts to the legendary social reformer and visionary king of Kolhapur, Rajarshi Chhatrapati Shahu Maharaj, proudly continuing the significant path shown by His Highness."</p>
                </div>
            </div>
        </section>

        <!-- Facilities Section -->
        <section class="section" id="facilities">
            <div class="container">
                <div class="section-header">
                    <h2>Hostel Facilities</h2>
                    <p>We provide modern amenities to ensure your stay is comfortable, productive, and memorable.</p>
                </div>
                <div class="card-grid">
                    <div class="card">
                        <span class="card-icon">🛏️</span>
                        <h3>Spacious Rooms</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">📶</span>
                        <h3>High-Speed 100 Mbps WiFi</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">🧹</span>
                        <h3>Daily Housekeeping Service</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">🔒</span>
                        <h3>24/7 CCTV Security</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">💡</span>
                        <h3>Power Backup Generator</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">🚿</span>
                        <h3>Hot Water Supply</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">📚</span>
                        <h3>Study Room & Library</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">🅿️</span>
                        <h3>Secure Parking Space</h3>
                    </div>
                    <div class="card">
                        <span class="card-icon">🎮</span>
                        <h3>Recreation & Common Area</h3>

                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="section" id="gallery">
            <div class="container">
                <div class="section-header">
                    <h2>Hostel Gallery</h2>
                    <p>Take a virtual tour of our hostel facilities and see what makes HOSTELORBIT perfect home for students.</p>
                </div>
                <div class="gallery-grid">
                    <div class="gallery-item">
                        <img src="images/img1.png" alt="Spacious Double Sharing Room">
                    </div>
                    <div class="gallery-item">
                        <img src="images/img2.png" alt="Comfortable Single AC Room">
                    </div>
                    <div class="gallery-item">
                        <img src="images/img3.png" alt="Modern Common Area">
                    </div>
                    <div class="gallery-item">
                        <img src="images/img4.png" alt="Well-lit Entrance">
                    </div>
                    <div class="gallery-item">
                        <img src="images/img5.png" alt="Clean Corridors">
                    </div>
                    <div class="gallery-item">
                        <img src="images/img6.png" alt="Study Room">
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="section" id="testimonials">
            <div class="container">
                <div class="section-header">
                    <h2>What Our Students Say</h2>
                </div>
                <div class="testimonial-grid">
                    <div class="testimonial-card">
                        <p>
                            "Sarswat Bording has been my home for past 2 years. The environment is perfect for studies, and staff is very supportive. The WiFi speed is excellent for online classes!"
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">A</div>
                            <div class="author-info">
                                <p>Amit Sharma</p>
                                <p>B.Tech Computer Science, 3rd Year</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p>
                            "The best thing about SBK is security and cleanliness. My parents are very happy with facilities. The wardens are like family members who care about us."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">P</div>
                            <div class="author-info">
                                <p>Priya Patel</p>
                                <p>MBA, 1st Year</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p>
                            "Great location near campus and market. The rooms are spacious and well-maintained. I especially love study room where I can focus on my preparations without any disturbance."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">R</div>
                            <div class="author-info">
                                <p>Rahul Verma</p>
                                <p>B.Com, 2nd Year</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section" id="contact">
            <div class="container">
                <div class="section-header">
                    <h2>Get In Touch</h2>
                    <p>Have questions? We're here to help! Visit us or reach out through any of the following channels.</p>
                </div>
                <div class="contact-grid">
                    <div class="contact-card">
                        <h4><span>📍</span> Address</h4>
                        <p>Sarswat Bording Kolhapur<br>
                        Near SBI bank <br>
                        Dasara Chowk Kolhapur 416002</p>
                    </div>
                    <div class="contact-card">
                        <h4><span>📞</span> Phone</h4>
                        <p>Warden Office: +91 83790 37877<br>
                        Admission Enquiry: +91 8379 037877<br>
                        Emergency: +83790 37877<br>
                        Landline: 022-12345678</p>
                    </div>
                    <div class="contact-card">
                        <h4><span>✉️</span> Email</h4>
                        <p>General: info@smarthostel.com<br>
                        Admissions: admissions@smarthostel.com<br>
                        Support: patildipak.vck@gmail.com</p>
                    </div>
                    <div class="contact-card">
                        <h4><span>🕐</span> Office Hours</h4>
                        <p>Monday - Saturday: 9:00 AM - 7:00 PM<br>
                        Sunday: 10:00 AM - 5:00 PM<br>
                        24/7 Emergency Support Available</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>HOSTELORBIT</h3>
                    <p>Premium student accommodation providing safe, comfortable, and affordable living spaces since 2018.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#facilities">Facilities</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>For Students</h4>
                    <ul>
                        <li><a href="login.php?role=student">Student Login</a></li>
                        <li><a href="login.php?role=admin">Admin Login</a></li>
                        <li><a href="login.php?role=staff">Staff Login</a></li>
                        <li><a href="#contact">Contact Support</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#">📘</a>
                        <a href="#">📷</a>
                        <a href="#">🐦</a>
                        <a href="#">💼</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 HOSTELORBIT. All Rights Reserved. | Developed by Dipak Patil</p>
              <a href="https://www.instagram.com/gavdiche_patil/" 
   target="_blank"
   style="
      text-decoration: none;
      color: #E1306C;
      font-weight: 600;
      font-size: 15px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
   ">
   📷 Instagram
</a>

            </div>
        </div>
    </footer>

    <script>
        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const loginBtn = document.getElementById('loginBtn');
            const loginDropdown = document.getElementById('loginDropdown');
            
            if (loginBtn && loginDropdown) {
                loginBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    loginDropdown.classList.toggle('show');
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.sidebar-dropdown')) {
                        loginDropdown.classList.remove('show');
                    }
                });
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Active menu highlighting
            const sections = document.querySelectorAll('section[id]');
            const menuLinks = document.querySelectorAll('.sidebar-menu a');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (pageYOffset >= sectionTop - 200) {
                        current = section.getAttribute('id');
                    }
                });

                menuLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').slice(1) === current) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>