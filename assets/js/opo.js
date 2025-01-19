document.addEventListener('DOMContentLoaded', () => {
    const opporTableBody = document.getElementById('oppor-table-body');
    const opporModal = new bootstrap.Modal(document.getElementById('opporModal'));
    const opporForm = document.getElementById('oppor-form');
    const createBtn = document.getElementById('oppor-create-btn');

    let isEditing = false;
    let currentRow = null;

    // Open Modal for Creating Opportunity
    createBtn.addEventListener('click', () => {
        opporForm.reset();
        isEditing = false;
        currentRow = null;
        document.getElementById('opporModalLabel').textContent = 'Create Opportunity';
        opporModal.show();
    });

    // Handle Form Submission
    opporForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const id = document.getElementById('oppor-id').value;
        const title = document.getElementById('oppor-title').value;
        const stage = document.getElementById('oppor-stage').value;
        const revenue = document.getElementById('oppor-revenue').value;
        const closeDate = document.getElementById('oppor-close-date').value;
        const probability = document.getElementById('oppor-probability').value;

        if (isEditing && currentRow) {
            // Update Existing Row
            currentRow.children[0].textContent = id;
            currentRow.children[1].textContent = title;
            currentRow.children[2].textContent = stage;
            currentRow.children[3].textContent = revenue;
            currentRow.children[4].textContent = closeDate;
            currentRow.children[5].textContent = probability;
        } else {
            // Create New Row
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${id}</td>
                <td>${title}</td>
                <td>${stage}</td>
                <td>${revenue}</td>
                <td>${closeDate}</td>
                <td>${probability}</td>
                <td>
                    <button class="btn btn-sm btn-update">
                        <i class="fas fa-edit"></i> Update
                    </button>
                    <button class="btn btn-sm btn-delete">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            `;
            opporTableBody.appendChild(newRow);
        }

        opporModal.hide();
    });

    // Handle Update and Delete Actions
    opporTableBody.addEventListener('click', (event) => {
        const button = event.target.closest('button');
        const row = button.closest('tr');

        if (button && button.classList.contains('btn-update')) {
            // Update Action
            isEditing = true;
            currentRow = row;
            document.getElementById('opporModalLabel').textContent = 'Edit Opportunity';

            document.getElementById('oppor-id').value = row.children[0].textContent;
            document.getElementById('oppor-title').value = row.children[1].textContent;
            document.getElementById('oppor-stage').value = row.children[2].textContent;
            document.getElementById('oppor-revenue').value = row.children[3].textContent;
            document.getElementById('oppor-close-date').value = row.children[4].textContent;
            document.getElementById('oppor-probability').value = row.children[5].textContent;

            opporModal.show();
        } else if (button && button.classList.contains('btn-delete')) {
            // Delete Action
            if (confirm('Are you sure you want to delete this opportunity?')) {
                row.remove();
            }
        }
    });
});
