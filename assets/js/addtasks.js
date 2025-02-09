document.addEventListener("DOMContentLoaded", function () {
  const taskTable = document.querySelector("tbody#table tbody");

  // Add Task Form Submission
  document
    .querySelector("#addTaskModal form")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      const taskId = document.getElementById("taskId").value;
      const description = document.getElementById("description").value;
      const dueDate = document.getElementById("dueDate").value;
      const assignedTo = document.getElementById("assignedTo").value;
      const customerId = document.getElementById("customerId").value;

      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${taskId}</td>
        <td>${description}</td>
        <td>${dueDate}</td>
        <td>${assignedTo}</td>
        <td>${customerId}</td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateTaskModal">Update</button>
          <button class="btn btn-danger btn-sm">Delete</button>
        </td>
      `;

      // Append row to the table
      taskTable.appendChild(row);

      // Reset the form
      this.reset();
      bootstrap.Modal.getInstance(
        document.querySelector("#addTaskModal")
      ).hide();
    });

  // Handle Update Button Click
  taskTable.addEventListener("click", function (event) {
    if (event.target.classList.contains("btn-warning")) {
      const row = event.target.closest("tr");
      document.getElementById("updateTaskId").value =
        row.children[0].textContent;
      document.getElementById("updateDescription").value =
        row.children[1].textContent;
      document.getElementById("updateDueDate").value =
        row.children[2].textContent;
      document.getElementById("updateAssignedTo").value =
        row.children[3].textContent;
      document.getElementById("updateCustomerId").value =
        row.children[4].textContent;
    }
  });

  // Update Task Form Submission
  document
    .querySelector("#updateTaskModal form")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      const taskId = document.getElementById("updateTaskId").value;
      const description = document.getElementById("updateDescription").value;
      const dueDate = document.getElementById("updateDueDate").value;
      const assignedTo = document.getElementById("updateAssignedTo").value;
      const customerId = document.getElementById("updateCustomerId").value;

      // Find the corresponding row to update
      const rows = taskTable.querySelectorAll("tr");
      rows.forEach((row) => {
        if (row.children[0].textContent === taskId) {
          row.children[1].textContent = description;
          row.children[2].textContent = dueDate;
          row.children[3].textContent = assignedTo;
          row.children[4].textContent = customerId;
        }
      });

      bootstrap.Modal.getInstance(
        document.querySelector("#updateTaskModal")
      ).hide();
    });

  // Handle Delete Button Click
  taskTable.addEventListener("click", function (event) {
    if (event.target.classList.contains("btn-danger")) {
      const row = event.target.closest("tr");
      row.remove();
    }
  });
});
