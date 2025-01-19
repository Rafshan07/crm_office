document.addEventListener("DOMContentLoaded", () => {
    // Handle Refresh Button
    const refreshButton = document.querySelector(".btn-primary");
    if (refreshButton) {
        refreshButton.addEventListener("click", () => {
            alert("Refreshing reports...");
            // Simulate refresh logic
            location.reload();
        });
    }

    // Delegate Events for View and Delete Buttons
    const container = document.querySelector(".container");
    if (container) {
        container.addEventListener("click", (e) => {
            if (e.target.classList.contains("btn-info")) {
                // Handle View Button
                const reportTitle = e.target.closest(".card-body").querySelector(".card-title").innerText;
                alert(`Viewing ${reportTitle}`);
                // Add your 'View Report' logic here
            } else if (e.target.classList.contains("btn-danger")) {
                // Handle Delete Button
                const card = e.target.closest(".card");
                const reportTitle = card.querySelector(".card-title").innerText;
                if (confirm(`Are you sure you want to delete ${reportTitle}?`)) {
                    card.remove();
                }
            }
        });
    }
});
