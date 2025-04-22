<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find the best computer hardware including processors, graphics cards, and RAM at our shop. Competitive prices and great services.">
    <meta property="og:title" content="CompCare Online">
    <meta property="og:description" content="Shop for the best computer hardware including processors, RAM, graphics cards, and more.">
    <meta property="og:image" content="favicon.png">
    <meta property="og:url" content="https://yourwebsite.com">
    <title>CompCare Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: silver;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            border-radius: 10px;
            object-fit: cover;
            width: auto;
            height: 360px;
        }

        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .highlighted-container {
            background-color: #f8f9fa;
            border: 2px solid #ffbb33;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #backToTopBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        #backToTopBtn:hover {
            background-color: #0056b3;
        }

        .about-section, .services-section {
            padding: 40px 0;
        }

        .about-section h2, .services-section h2 {
            font-size: 2rem;
            color: #333;
        }

        .about-section p, .services-section p {
            font-size: 1.2rem;
            color: #555;
        }

        .about-section img, .services-section img {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 20px;
        }

        .service-item {
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        .no-products {
            text-align: center;
            font-size: 1.5rem;
            color: #555;
        }
    </style>
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>CompCare Online</h1>
    </header>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="chs.php">Computer Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="index.php">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="chs.php"><i class="fa fa-home"></i></a></li>
                </ul>
                
                <form class="d-flex">
                    <select id="categorySelect" class="form-control me-2" aria-label="Select Category">
                        <option value="">Select Category</option>
                        <option value="Processors">Processors</option>
                        <option value="Graphics Cards">Graphics Cards</option>
                        <option value="RAM">RAM</option>
                        <option value="Storage">Storage</option>
                        <option value="Power Supply">Power Supply</option>
                        <option value="Monitors">Monitors</option>
                    </select>
                    <input id="searchBox" class="form-control me-2" type="search" placeholder="Search Products" aria-label="Search">
                    <button class="btn btn-outline-light" type="button" id="searchBtn">Search</button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="register.php">
                        <i class="fas fa-user"></i>
                    </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
   
    <!-- Products Section -->
    <div class="container my-4 highlighted-container">
        <h2 id="products" class="text-center">Trending Products</h2>
        <div class="row" id="productList"></div>
        <p id="noProductsMessage" class="no-products" style="display: none;">No products found matching your criteria.</p>
    </div>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="text-center">Our Services</h2>
            <p class="text-center">At Computer Hardware Shop, we offer a wide range of services to make your shopping experience as seamless as possible. Here are some of the services we provide:</p>
            <div class="row">
                <div class="col-md-4 service-item">
                    <div class="card text-center p-3">
                        <img src="installation.jpg" class="card-img-top" alt="Installation Service" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title">Installation Services</h5>
                            <p class="card-text">Our team will help you set up your new hardware components, ensuring everything works smoothly.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 service-item">
                    <div class="card text-center p-3">
                        <img src="repair.jpg" class="card-img-top" alt="Repair Services" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title">Repair Services</h5>
                            <p class="card-text">We offer professional repair services for your hardware, ensuring it gets back to working condition.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 service-item">
                    <div class="card text-center p-3">
                        <img src="consultation.jpg" class="card-img-top" alt="Consultation Services" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title">Consultation Services</h5>
                            <p class="card-text">Need advice on building the perfect PC? Our experts are here to guide you through the process.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2 class="text-center">About Us</h2>
            <p class="text-center">We are a trusted provider of computer hardware, offering a wide range of products including processors, graphics cards, memory modules, and storage devices. Our mission is to provide high-quality components at competitive prices, backed by excellent customer service.</p>
            <div class="row">
                <div class="col-md-6">
                    <h4>Our Story</h4>
                    <p>Founded in 2025, Computer Hardware Shop started as a small business with a vision to provide affordable and high-quality computer components to tech enthusiasts and professionals. Over the years, we've grown into a leading supplier, providing products from top brands and offering expert advice to help you build the best systems.</p>
                </div>
                <div class="col-md-6">
                    <h4>Why Choose Us?</h4>
                    <ul>
                        <li>Competitive Prices</li>
                        <li>Wide Selection of Products</li>
                        <li>Expert Customer Support</li>
                        <li>Fast Shipping</li>
                    </ul>
                </div>
            </div>
            <div class="text-center">
                <img src="store-image.jpg" alt="Store Image">
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p>Contact us at: ramesh@hardwarestore.com | Call: +91 9360656401</p>
        <p>&copy; Computer Hardware Shop. All rights reserved.</p>
        <p>We Accept:</p>
        <div>
            <i class="fab fa-cc-visa fa-2x mx-2"></i>
            <i class="fab fa-cc-mastercard fa-2x mx-2"></i>
            <i class="fab fa-cc-paypal fa-2x mx-2"></i>
            <i class="fab fa-google-pay fa-2x mx-2"></i>
            <i class="fab fa-cc-amex fa-2x mx-2"></i>
        </div>
    </footer>
    
    <button id="backToTopBtn" title="Go to top">Top</button>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productList = document.getElementById("productList");
            const noProductsMessage = document.getElementById("noProductsMessage");
            const categorySelect = document.getElementById("categorySelect");
            const searchBox = document.getElementById("searchBox");
            const searchBtn = document.getElementById("searchBtn");

            // Product data array
            const products = [
                { name: "Processor", image: "i9-13.jpg", description: "High-performance processors.", price: 60000, category: "Processors", link: "product1.php" },
                { name: "Graphics Card", image: "gpu.jpg", description: "Powerful GPUs for gaming.", price: 75000, category: "Graphics Cards", link: "product2.php" },
                { name: "RAM 16GB", image: "ram.jpg", description: "Fast RAM for better performance.", price: 8000, category: "RAM", link: "product3.php" },
                { name: "SSD 1TB", image: "ssd.jpg", description: "High-speed NVMe SSD.", price: 15000, category: "Storage", link: "product4.php" },
                { name: "Power Supply 750W", image: "psu.jpg", description: "Stable power supply.", price: 6000, category: "Power Supply", link: "product5.php" },
                { name: "Gaming Monitor", image: "monitor.jpg", description: "High refresh rate display.", price: 12000, category: "Monitors", link: "product6.php" }
            ];

            // Render products dynamically
            function renderProducts(productArray) {
                productList.innerHTML = "";
                if (productArray.length === 0) {
                    noProductsMessage.style.display = "block";
                } else {
                    noProductsMessage.style.display = "none";
                    productArray.forEach(product => {
                        const productCard = `
                            <div class="col-md-4 product" data-name="${product.name}">
                                <a href="${product.link}" class="card text-center p-3">
                                    <img src="${product.image}" class="card-img-top" alt="${product.name}" loading="lazy">
                                    <div class="card-body">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text">${product.description}</p>
                                        <p class="card-text"><strong>Price: Rs. ${product.price}</strong></p>
                                    </div>
                                </a>
                            </div>
                        `;
                        productList.innerHTML += productCard;
                    });
                }
            }

            // Initially render all products
            renderProducts(products);

            // Search and category filter functionality
            function filterProducts() {
                const searchText = searchBox.value.toLowerCase();
                const selectedCategory = categorySelect.value;

                const filteredProducts = products.filter(product => {
                    const matchesSearch = product.name.toLowerCase().includes(searchText);
                    const matchesCategory = selectedCategory ? product.category === selectedCategory : true;
                    return matchesSearch && matchesCategory;
                });

                renderProducts(filteredProducts);
            }

            // Event listeners
            searchBox.addEventListener("input", filterProducts);
            searchBtn.addEventListener("click", filterProducts);
            categorySelect.addEventListener("change", filterProducts);

            // Back-to-top button functionality
            const backToTopBtn = document.getElementById("backToTopBtn");
            window.addEventListener("scroll", function () {
                backToTopBtn.style.display = window.scrollY > 20 ? "block" : "none";
            });
            backToTopBtn.addEventListener("click", function () {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
