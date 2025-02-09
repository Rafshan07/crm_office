// Populate customer table dynamically
const customerTable = document.getElementById("customer-data-table");

customers.forEach((customer) => {
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
