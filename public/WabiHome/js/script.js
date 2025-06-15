
document.addEventListener('DOMContentLoaded', function () {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Add staggered animation delay to products
    const products = document.querySelectorAll('.card');
    products.forEach((product, index) => {
        product.setAttribute('data-aos-delay', 100 + (index % 3) * 100);
    });

    // Add hover effect for card images
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.classList.add('shadow-lg');
        });
        card.addEventListener('mouseleave', function () {
            this.classList.remove('shadow-lg');
        });
    });

    // Real-time search functionality
    const searchInput = document.getElementById('realTimeSearch');
    const productCards = document.querySelectorAll('.col-md-4.mb-4');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();

            productCards.forEach(card => {
                const productTitle = card.querySelector('.card-title').textContent.toLowerCase();
                const productDescription = card.querySelector('.card-text').textContent.toLowerCase();

                if (productTitle.includes(searchTerm) || productDescription.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Category filter functionality
    const categoryLinks = document.querySelectorAll('.category-link');

    categoryLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active class from all links
            categoryLinks.forEach(l => l.classList.remove('active'));

            // Add active class to clicked link
            this.classList.add('active');

            const category = this.getAttribute('data-category');

            if (category === 'all') {
                productCards.forEach(card => {
                    card.style.display = 'block';
                });
            } else {
                productCards.forEach(card => {
                    // In a real implementation, each card would have a data-category attribute
                    // For this demo, we'll alternate assigning IOTM and VOTM to the products
                    const cardIndex = Array.from(productCards).indexOf(card);
                    const cardCategory = cardIndex % 2 === 0 ? 'iotm' : 'votm';

                    if (cardCategory === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });
    });
});