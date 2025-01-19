document.addEventListener("DOMContentLoaded", () => {
    const products = [
        { name: "CRM Software", users: 150, img: "./assets/image/logo.png" },
        { name: "Project Management Tool", users: 85, img: "./assets/image/logo.png" },
        { name: "HR Management System", users: 120, img: "./assets/image/logo.png" },
    ];

    const template = document.getElementById("product-card-template");
    const container = template.parentElement;

    products.forEach(product => {
        const card = template.cloneNode(true);
        card.style.display = "block";
        card.querySelector(".product-img").src = product.img;
        card.querySelector(".product-name").textContent = product.name;
        card.querySelector(".product-users").textContent = `Users: ${product.users}`;

        // Add event listener for "View Details" button
        card.querySelector(".view-details").addEventListener("click", () => {
            alert(`Viewing details for: ${product.name}`);
        });

        container.appendChild(card);
    });

    template.remove();
});