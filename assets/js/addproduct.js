document.addEventListener('DOMContentLoaded', function () {
    const productTable = document.querySelector('table tbody');
    const photoInput = document.getElementById('productPhoto');
    const photoPreview = document.getElementById('photoPreview');

    // Show preview of uploaded photo
    photoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            photoPreview.src = '';
            photoPreview.style.display = 'none';
        }
    });

    // Add Product
    document.querySelector('#addProductForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const productId = document.getElementById('productId').value;
        const name = document.getElementById('productName').value;
        const category = document.getElementById('productCategory').value;
        const price = document.getElementById('productPrice').value;
        const stockLevel = document.getElementById('stockLevel').value;

        // Check if a photo is uploaded
        let photoSrc = '';
        const file = photoInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                photoSrc = e.target.result;
                appendProductRow(productId, photoSrc, name, category, price, stockLevel);
            };
            reader.readAsDataURL(file);
        } else {
            appendProductRow(productId, '', name, category, price, stockLevel);
        }

        // Reset form and modal
        this.reset();
        photoPreview.style.display = 'none';
        bootstrap.Modal.getInstance(document.querySelector('#addProductModal')).hide();
    });

    // Add row to the table
    function appendProductRow(id, photo, name, category, price, stock) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${id}</td>
            <td>${photo ? `<img src="${photo}" alt="${name}" width="50">` : 'No Photo'}</td>
            <td>${name}</td>
            <td>${category}</td>
            <td>${price}</td>
            <td>${stock}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-product">Edit</button>
                <button class="btn btn-danger btn-sm delete-product">Delete</button>
            </td>
        `;
        productTable.appendChild(row);
    }

    // Edit and Delete Buttons
    productTable.addEventListener('click', function (event) {
        const target = event.target;

        if (target.classList.contains('delete-product')) {
            target.closest('tr').remove(); // Remove product
        } else if (target.classList.contains('edit-product')) {
            const row = target.closest('tr');
            const cells = row.children;

            // Populate modal with current product details
            document.getElementById('productId').value = cells[0].textContent;
            photoPreview.src = cells[1].querySelector('img')?.src || '';
            photoPreview.style.display = cells[1].querySelector('img') ? 'block' : 'none';
            document.getElementById('productName').value = cells[2].textContent;
            document.getElementById('productCategory').value = cells[3].textContent;
            document.getElementById('productPrice').value = cells[4].textContent;
            document.getElementById('stockLevel').value = cells[5].textContent;

            // Remove existing row after editing
            row.remove();

            // Show add modal with pre-filled data
            bootstrap.Modal.getOrCreateInstance(document.querySelector('#addProductModal')).show();
        }
    });
});
