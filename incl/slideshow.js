// Function to filter products by category
function filterCategory(category) {
    var products = document.querySelectorAll('.product');
    products.forEach(function(product) {
        var productCategory = product.getAttribute('data-category');
        if (category === 'all' || productCategory === category) {
            product.classList.add('show');
        } else {
            product.classList.remove('show');
        }
    });
    updateActiveButton(category);
}

// Function to update active category button
function updateActiveButton(category) {
    var buttons = document.querySelectorAll('.category-links button');
    buttons.forEach(function(button) {
        if (button.textContent.toLowerCase() === category || (category === 'all' && button.textContent.toLowerCase() === 'all')) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

// Function to handle slideshow functionality (existing code)
var slideIndex = 0;

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    if (n >= slides.length) {
        slideIndex = 0;
    }
    if (n < 0) {
        slideIndex = slides.length - 1;
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; // Hide all slides
    }
    slides[slideIndex].style.display = "block"; // Show the current slide
}

function nextSlide() {
    slideIndex++;
    if (slideIndex >= document.getElementsByClassName("mySlides").length) {
        slideIndex = 0;
    }
    showSlides(slideIndex);
}

function prevSlide() {
    slideIndex--;
    if (slideIndex >= document.getElementsByClassName("mySlides").length) {
        slideIndex = 0;
    }
    showSlides(slideIndex);
}

setInterval(nextSlide, 5000); // Auto change slides every 5 seconds

// Initialize slideshow and product filtering
showSlides(slideIndex);
filterCategory('all'); // Show all products initially
