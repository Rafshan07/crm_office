// Query all nav links and buttons
const navLinks = document.querySelectorAll('.nav-link');

// Function to toggle active state and manage collapse behavior
navLinks.forEach(link => {
    // Add click event listener
    link.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default navigation
        event.stopPropagation(); // Stop event propagation

        // Manage active class for current link and collapse other open menus
        navLinks.forEach(otherLink => {
            if (otherLink !== link) {
                otherLink.classList.remove('active');
                const otherSubmenu = otherLink.nextElementSibling;
                if (otherSubmenu && otherSubmenu.classList.contains('collapse')) {
                    otherSubmenu.classList.remove('show');
                }
            }
        });

        // Toggle active state and submenu visibility
        link.classList.toggle('active');
        const submenu = link.nextElementSibling;
        if (submenu && submenu.classList.contains('collapse')) {
            submenu.classList.toggle('show');
        }
    });

    // Hover effect: highlight the link
    link.addEventListener('mouseenter', () => link.classList.add('hovered'));
    link.addEventListener('mouseleave', () => link.classList.remove('hovered'));
});

// Close all menus when clicking outside the sidebar
document.addEventListener('click', () => {
    navLinks.forEach(link => {
        link.classList.remove('active');
        const submenu = link.nextElementSibling;
        if (submenu && submenu.classList.contains('collapse')) {
            submenu.classList.remove('show');
        }
    });
});

// Add pointer cursor to all nav links for better user experience
navLinks.forEach(link => {
    link.style.cursor = 'pointer';
});
