<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocVerify AI - Sri Lankan Document Verification System</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles_index.css"/>
</head>
<body>
<!-- Header & Navigation -->
<header>
    <div class="container">
        <nav>
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-id-card-alt"></i>
                </div>
                <span>DocVerify AI</span>
            </div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#about">About</a>
            </div>
            <a href="admin_login.php" class="btn btn-primary">Admin Login</a>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>AI and OCR Based System for Verifying and Extracting Data from Sri Lankan IDs and Billing Documents</h1>
            <p>Automate identity verification with our specialized AI system designed for Sri Lankan documents. Reduce errors, save time, and enhance security.</p>
            <div class="hero-buttons">
                <a href="#how-it-works" class="btn btn-primary">Learn More</a>
                <a href="admin_login.php" class="btn btn-outline">Admin Login</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container">
        <div class="section-title">
            <h2>Powerful Features</h2>
            <p>Our specialized AI system is designed specifically for Sri Lankan document verification needs</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Document Classification</h3>
                <p>Automatically identifies NICs, passports, driving licenses, and utility bills using advanced machine learning.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Field Detection</h3>
                <p>Precisely locates key information fields like names, addresses, and identification numbers on documents.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-font"></i>
                </div>
                <h3>OCR Text Extraction</h3>
                <p>Extracts text from detected fields with high accuracy, even from low-quality images.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Data Validation</h3>
                <p>Validates extracted information using rule-based checks and regular expressions.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Fast Processing</h3>
                <p>Processes documents in seconds, dramatically reducing verification time compared to manual methods.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Secure & Compliant</h3>
                <p>Designed to comply with Sri Lanka's data protection regulations and security requirements.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-title">
            <h2>How It Works</h2>
            <p>Our four-step process simplifies document verification for Sri Lankan organizations</p>
        </div>
        <div class="process-steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Upload Document</h3>
                <p>User uploads a scanned image or photo of their identity document</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>AI Classification</h3>
                <p>System identifies the document type (NIC, license, etc.)</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Data Extraction</h3>
                <p>Key fields are detected and text is extracted using OCR</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Verification</h3>
                <p>Extracted data is validated and structured for use</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about" id="about">
    <div class="container">
        <div class="section-title">
            <h2>About The Project</h2>
            <p>Developed as part of CSE6035 - Development Project</p>
        </div>
        <div class="about-content">
            <div class="about-text">
                <p>This AI-powered document verification system was developed to address the specific challenges faced by Sri Lankan organizations in verifying identity documents. Unlike international solutions, our system is specially trained to recognize and process Sri Lankan document formats, languages, and layouts.</p>
                <p>The system leverages cutting-edge technologies including machine learning for document classification, object detection for field localization, and OCR for text extraction. It's designed to integrate seamlessly with existing workflows through a web interface or API.</p>
                <br>
                <br>
                <p><strong>Developer:</strong> Arvin Jayanake<br>
                    <strong>Course:</strong> BSc (Hons) Software Engineering<br>
                    <strong>ICBT ID:</strong>CL/BSCSD/29/01</p>
                    <strong>Cardiff ID:</strong>ST20250868</p>
                <br>
                <a href="admin_login.php" class="btn btn-primary">Access Admin Panel</a>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1587483169554-f9bdd1dc9fe5?q=80&w=600" alt="About Document Verification">
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-column">
                <h3>DocVerify AI</h3>
                <p>Sri Lanka's premier AI-powered document verification system designed for local needs.</p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="admin_login.php">Admin Login</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Document Types</h3>
                <ul>
                    <li><a href="#">National Identity Cards</a></li>
                    <li><a href="#">Driving Licenses</a></li>
                    <li><a href="#">Passports</a></li>
                    <li><a href="#">Utility Bills</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <ul>
                    <li><i class="fas fa-envelope"></i> arvinjnk05@gmail.com</li>
                    <li><i class="fas fa-phone"></i> +94771775727</li>
                    <li><i class="fas fa-map-marker-alt"></i> Colombo, Sri Lanka</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 Arvin Jayanake. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>