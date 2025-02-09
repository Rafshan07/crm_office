 // Function to add a new item row to the invoice table
 function addItem() {
    const tableBody = document.querySelector("#itemsTable tbody");
    const newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td><input type="text" class="form-control item-description" placeholder="Description"></td>
        <td><input type="number" class="form-control item-quantity" placeholder="Quantity" oninput="calculateAmount(this)"></td>
        <td><input type="number" class="form-control item-rate" placeholder="Rate" oninput="calculateAmount(this)"></td>
        <td><input type="number" class="form-control item-tax" placeholder="Tax (%)" oninput="calculateAmount(this)"></td>
        <td><span class="item-amount">0.00</span></td>
    `;
    tableBody.appendChild(newRow);
    updateInvoiceTotals();
}

// Function to calculate the total amount for each item and update the table
function calculateAmount(element) {
    const row = element.closest('tr');
    const quantity = row.querySelector('.item-quantity').value;
    const rate = row.querySelector('.item-rate').value;
    const tax = row.querySelector('.item-tax').value;

    const qty = parseFloat(quantity) || 0;
    const price = parseFloat(rate) || 0;
    const taxRate = parseFloat(tax) || 0;

    const amount = (qty * price) + ((qty * price) * taxRate / 100);
    row.querySelector('.item-amount').innerText = amount.toFixed(2);
    
    updateInvoiceTotals();
}

// Function to update the invoice totals (tax, total, due amount)
function updateInvoiceTotals() {
    let totalTax = 0;
    let total = 0;

    document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
        const amount = parseFloat(row.querySelector('.item-amount').innerText) || 0;
        total += amount;

        const quantity = row.querySelector('.item-quantity').value;
        const rate = row.querySelector('.item-rate').value;
        const tax = row.querySelector('.item-tax').value;

        totalTax += (parseFloat(quantity) * parseFloat(rate)) * (parseFloat(tax) / 100);
    });

    document.getElementById("tax").innerText = `BDT ${totalTax.toFixed(2)}`;
    document.getElementById("total").innerText = `BDT ${total.toFixed(2)}`;
    updateDueAmount();
}

// Function to update the due amount (total - amount paid)
function updateDueAmount() {
    const totalAmount = parseFloat(document.getElementById("total").innerText.replace('BDT ', '') || 0);
    const amountPaid = parseFloat(document.getElementById("amountPaid").value || 0);

    const dueAmount = totalAmount - amountPaid;

    document.getElementById("dueAmount").value = dueAmount.toFixed(2);
    document.getElementById("amountPaidText").innerText = amountPaid.toFixed(2);

    calculateBalance();
}

// Function to calculate and display the balance due
function calculateBalance() {
    const totalAmount = parseFloat(document.getElementById("total").innerText.replace('BDT ', '') || 0);
    const amountPaid = parseFloat(document.getElementById("amountPaid").value || 0);

    const balanceDue = totalAmount - amountPaid;

    document.getElementById("balanceDue").innerText = `BDT ${balanceDue.toFixed(2)}`;
}

// Function to generate and show the invoice preview
function showInvoice() {
    const itemsTable = document.querySelector("#itemsTable tbody");
    const invoiceItemsPreview = document.querySelector("#invoiceItemsPreview tbody");

    invoiceItemsPreview.innerHTML = "";

    itemsTable.querySelectorAll("tr").forEach(row => {
        const itemDescription = row.querySelector('.item-description').value || '';
        const itemQuantity = row.querySelector('.item-quantity').value || 0;
        const itemRate = row.querySelector('.item-rate').value || 0;
        const itemAmount = row.querySelector('.item-amount').innerText || 0;

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${itemDescription}</td>
            <td>${itemQuantity}</td>
            <td>${itemRate}</td>
            <td>${itemAmount}</td>
        `;
        invoiceItemsPreview.appendChild(tr);
    });

    // Update preview with the totals and other details
    document.getElementById("taxPreview").innerText = document.getElementById("tax").innerText;
    document.getElementById("totalPreview").innerText = document.getElementById("total").innerText;
    document.getElementById("amountPaidPreview").innerText = document.getElementById("amountPaidText").innerText;
    document.getElementById("balanceDuePreview").innerText = document.getElementById("balanceDue").innerText;

    document.getElementById("invoicePopup").style.display = "flex";
    
    // Set invoice date and number
    const invoiceDate = new Date().toLocaleDateString();
    document.getElementById("invoiceDatePreview").innerText = invoiceDate;
    document.getElementById("invoiceNumberPreview").innerText = "INV" + Math.floor(Math.random() * 1000);
}

// Function to close the invoice preview popup
function closeInvoicePreview() {
    document.getElementById("invoicePopup").style.display = "none";
}




function printInvoice() {
    // Create a new window for the invoice content
    let win = window.open('', '', 'width=800,height=600');

    // Get the content of the invoice you want to print
    const invoiceContent = document.querySelector('.invoice-wrapper').innerHTML;

    // Write the content into the new window
    win.document.write('<html><head><title>Invoice</title>');
    
    // Add any additional CSS styles for printing
    win.document.write(`
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }
            .invoice-wrapper {
                max-width: 800px;
                margin: auto;
                padding: 20px;
                background-color: #f9f9f9;
                border: 1px solid #ccc;
            }
            .invoice-header, .invoice-info, .invoice-table, .invoice-summary {
                width: 100%;
                margin-bottom: 20px;
            }
            .invoice-table th, .invoice-table td {
                padding: 10px;
                text-align: left;
            }
            .invoice-thanks {
                text-align: center;
                margin-top: 20px;
            }
            @media print {
                .invoice-wrapper {
                    margin: 0;
                    padding: 0;
                    border: none;
                }
            }
        </style>
    `);
    
    // Add the invoice content to the window's body
    win.document.write('</head><body>');
    win.document.write(invoiceContent);
    win.document.write('</body></html>');

    // Close the document and trigger print dialog
    win.document.close();
    win.print();
}




































