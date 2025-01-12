document.querySelectorAll('.nav-button').forEach(button => {
    button.addEventListener('click', (event) => {
        // Prevent click from propagating to the document
        event.stopPropagation();

        // Remove the active class from all buttons except the clicked one
        document.querySelectorAll('.nav-button').forEach(item => {
            if (item !== button) {
                item.classList.remove('active');
            }
        });

        // Toggle the active class on the clicked button
        button.classList.toggle('active');
    });

    // Hover in: add active class
    button.addEventListener('mouseenter', () => {
        button.classList.add('active');
    });

    // Hover out: remove active class when mouse leaves button
    button.addEventListener('mouseleave', () => {
        button.classList.remove('active');
    });
});

// Add event listener to the document for closing menus
document.addEventListener('click', () => {
    // Remove the active class from all buttons
    document.querySelectorAll('.nav-button').forEach(button => {
        button.classList.remove('active');
    });
});

// Change cursor to pointer for nav-buttons
document.querySelectorAll('.nav-button').forEach(button => {
    button.style.cursor = 'pointer';
});