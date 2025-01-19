
const customers = [
    {
        id: "C001",
        name: "John Doe",
        email: "john@example.com",
        phone: "123-456-7890",
        address: "123 Main St, City",
        company: "ABC Corp",
        industry: "Technology",
        photo: "https://via.placeholder.com/50",
        tasks: { id: "T001", dueDate: "2025-01-20", assignedTo: "Jane Smith" }
    },
    {
        id: "C002",
        name: "Alice Brown",
        email: "alice@example.com",
        phone: "234-567-8901",
        address: "456 Elm St, City",
        company: "XYZ Inc.",
        industry: "Finance",
        photo: "https://via.placeholder.com/50",
        tasks: { id: "T002", dueDate: "2025-01-25", assignedTo: "John Lee" }
    }
];

// Populate customer table dynamically
const customerTable = document.getElementById("customer-data-table");

customers.forEach(customer => {
    const row = document.createElement("tr");

    row.innerHTML = `
        <td>${customer.id}</td>
        <td>${customer.name}</td>
        <td>${customer.email}</td>
        <td>${customer.phone}</td>
        <td>${customer.address}</td>
        <td>${customer.company}</td>
        <td>${customer.industry}</td>
        <td><img src="${customer.photo}" alt="Photo" class="img-fluid" style="max-width: 50px;"></td>
        <td>
            <button class="btn btn-primary btn-sm tasks-btn" data-id="${customer.tasks.id}" 
                data-due-date="${customer.tasks.dueDate}" 
                data-assigned-to="${customer.tasks.assignedTo}" 
                data-bs-toggle="modal" data-bs-target="#taskModal">
                Tasks
            </button>
        </td>
    `;

    customerTable.appendChild(row);
});

// Handle Tasks Button click to populate modal with task data
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("tasks-btn")) {
        const taskId = e.target.getAttribute("data-id");
        const dueDate = e.target.getAttribute("data-due-date");
        const assignedTo = e.target.getAttribute("data-assigned-to");

        // Set task details in the modal
        document.getElementById("task-id").textContent = taskId;
        document.getElementById("due-date").textContent = dueDate;
        document.getElementById("assigned-to").textContent = assignedTo;
    }
});
