document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('lead-table-body');
    const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
    const updateForm = document.getElementById('update-form');

    // Function to add a temporary active effect to buttons
    const addButtonFeedback = (button) => {
        button.classList.add('btn-active'); // Custom active class
        setTimeout(() => button.classList.remove('btn-active'), 200); // Remove after 200ms
    };

    // Handle Update Button Click
    tableBody.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-update')) {
            const button = event.target;
            addButtonFeedback(button); // Add visual feedback

            const row = button.closest('tr');
            const cells = row.querySelectorAll('td');

            // Populate modal fields with the row's data
            document.getElementById('lead-id').value = cells[0].textContent;
            document.getElementById('lead-source').value = cells[1].textContent;
            document.getElementById('lead-status').value = cells[2].textContent;
            document.getElementById('lead-assigned-to').value = cells[3].textContent;
            document.getElementById('lead-created-date').value = cells[4].textContent;
            document.getElementById('lead-customer-id').value = cells[5].textContent;

            // Show the modal
            updateModal.show();
        }
    });

    // Handle Save Changes in Modal
    updateForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const saveButton = document.querySelector('#updateModal .btn-primary');
        addButtonFeedback(saveButton); // Add visual feedback to save button

        // Update the table with modal data
        const leadId = document.getElementById('lead-id').value;
        const row = Array.from(tableBody.children).find(
            (tr) => tr.children[0].textContent === leadId
        );

        if (row) {
            row.children[1].textContent = document.getElementById('lead-source').value;
            row.children[2].textContent = document.getElementById('lead-status').value;
            row.children[3].textContent = document.getElementById('lead-assigned-to').value;
            row.children[4].textContent = document.getElementById('lead-created-date').value;
            row.children[5].textContent = document.getElementById('lead-customer-id').value;
        }

        // Hide the modal
        updateModal.hide();
    });

    // Handle Delete Button Click
    tableBody.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-delete')) {
            const button = event.target;
            addButtonFeedback(button); // Add visual feedback

            const confirmDelete = confirm('Are you sure you want to delete this lead?');
            if (confirmDelete) {
                const row = button.closest('tr');
                tableBody.removeChild(row);
            }
        }
    });
});
