document.addEventListener("DOMContentLoaded", function () {
    const users = [
        {
            id: "C001",
            name: "John Doe",
            email: "john@example.com",
            phone: "123-456-7890",
            address: "123 Street, NY",
            company: "Example Co.",
            industry: "Software",
            role: "Admin"  // Added role field
        },
        {
            id: "C002",
            name: "Jane Smith",
            email: "jane@example.com",
            phone: "987-654-3210",
            address: "456 Avenue, CA",
            company: "ABC Corp.",
            industry: "Finance",
            role: "User"  // Added role field
        }
    ];

    const tbody = document.getElementById("alluser-tbody");

    function populateTable() {
        tbody.innerHTML = "";
        users.forEach((user, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${user.address}</td>
                <td>${user.company}</td>
                <td>${user.industry}</td>
                <td>${user.role}</td> <!-- Added Role data -->
                <td>
                    <button class="btn btn-upgrade" data-id="${user.id}" data-bs-toggle="modal" data-bs-target="#editUserModal">Update</button>
                    <button class="btn btn-delete" data-id="${user.id}">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function handleUpgrade(event) {
        const userId = event.target.getAttribute("data-id");
        const user = users.find(u => u.id === userId);
        if (user) {
            document.getElementById("editCustomerId").value = user.id;
            document.getElementById("editName").value = user.name;
            document.getElementById("editEmail").value = user.email;
            document.getElementById("editPhone").value = user.phone;
            document.getElementById("editAddress").value = user.address;
            document.getElementById("editCompany").value = user.company;
            document.getElementById("editIndustry").value = user.industry;
        }
    }

    function handleDelete(event) {
        const userId = event.target.getAttribute("data-id");
        const userIndex = users.findIndex(u => u.id === userId);
        if (userIndex !== -1) {
            users.splice(userIndex, 1);
            populateTable();
        }
    }

    tbody.addEventListener("click", function (event) {
        if (event.target.classList.contains("btn-upgrade")) {  // Fixed typo here
            handleUpgrade(event);
        } else if (event.target.classList.contains("btn-delete")) {
            handleDelete(event);
        }
    });

    populateTable();
});
