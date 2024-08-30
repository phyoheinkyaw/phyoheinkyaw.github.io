// Set the current year in the footer
const footerYear = document.getElementById('footer-year');
footerYear.textContent = `© ${new Date().getFullYear()} John Doe. All Rights Reserved.`;

// Smooth scrolling for navigation links
document.querySelectorAll('.navbar-nav a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Activate navigation links on scroll
window.addEventListener('scroll', () => {
    let fromTop = window.scrollY + 60; // Offset for fixed navbar
    document.querySelectorAll('.navbar-nav a').forEach(link => {
        let section = document.querySelector(link.getAttribute('href'));
        if (section.offsetTop <= fromTop && section.offsetTop + section.offsetHeight > fromTop) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});
