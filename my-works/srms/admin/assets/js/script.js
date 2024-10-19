document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    const sidebarCollapse = document.getElementById("sidebarCollapse");
    const offcanvasSidebar = new bootstrap.Offcanvas(document.getElementById('offcanvasSidebar'));

    const counters = document.querySelectorAll('.counter');
    const speed = 1000; // Speed of the counter animation

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 100); // Adjust delay for smoother or faster animation
            } else {
                counter.innerText = target; // Ensure target value is displayed at the end
            }
        };

        updateCount();
    });

    // Toggle the Sidebar
    sidebarCollapse.addEventListener("click", function () {
        if (window.innerWidth > 991.9) {
            // Toggle the fixed sidebar for larger screens
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                content.classList.remove('expanded');
                content.style.marginLeft = "250px"; // Set content margin when sidebar is visible
            } else {
                sidebar.classList.add('collapsed');
                content.classList.add('expanded');
                content.style.marginLeft = "0"; // Remove content margin when sidebar is collapsed
            }
        } else {
            // Toggle offcanvas for smaller screens
            offcanvasSidebar.toggle();
        }
    });

    // Adjusting Content Margin for Larger Screens (Non-Mobile)
    window.addEventListener('resize', function () {
        if (window.innerWidth > 991.9) {
            offcanvasSidebar.hide();
            // Ensure the correct state of the sidebar and content when resizing
            if (sidebar.classList.contains('collapsed')) {
                content.style.marginLeft = "0";
                content.classList.add('expanded');
            } else {
                content.style.marginLeft = "250px";
                content.classList.remove('expanded');
            }
        } else {
            content.style.marginLeft = "0";
            content.classList.remove('expanded');
        }
    });
});
