document.addEventListener("DOMContentLoaded", () => {
    const orderTableBody = document.getElementById("orderTableBody");
    const customerSelect = document.getElementById("customerSelect");
    const orderDetailsBody = document.getElementById("orderDetailsBody");

    // Sample order and order details data
    const orders = [
        { orderId: "101", orderDate: "2025-01-01", status: "Pending", totalAmount: "$500", customerId: "1" },
        { orderId: "102", orderDate: "2025-01-02", status: "Completed", totalAmount: "$1200", customerId: "2" },
        { orderId: "103", orderDate: "2025-01-03", status: "Pending", totalAmount: "$750", customerId: "1" },
    ];

    const orderDetails = {
        "101": [
            { detailId: "D1011", productId: "P1001", quantity: 2, price: "$200" },
            { detailId: "D1012", productId: "P1002", quantity: 1, price: "$300" },
        ],
        "102": [
            { detailId: "D1021", productId: "P1003", quantity: 3, price: "$400" },
        ],
        "103": [
            { detailId: "D1031", productId: "P1001", quantity: 2, price: "$250" },
        ],
    };

    // Populate the table based on selected customer
    customerSelect.addEventListener("change", () => {
        const customerId = customerSelect.value;
        orderTableBody.innerHTML = orders
            .filter((order) => order.customerId === customerId)
            .map(
                (order) => `
                <tr>
                    <td>${order.orderId}</td>
                    <td>${order.orderDate}</td>
                    <td>${order.status}</td>
                    <td>${order.totalAmount}</td>
                    <td>${order.customerId}</td>
                    <td>
                        <button class="btn btn-success btn-sm mark-done" data-id="${order.orderId}">Done</button>
                        <button class="btn btn-primary btn-sm view-details" data-id="${order.orderId}" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">Details</button>
                    </td>
                </tr>
            `
            )
            .join("");
    });

    // Handle "Details" button click
    orderTableBody.addEventListener("click", (e) => {
        if (e.target.classList.contains("view-details")) {
            const orderId = e.target.getAttribute("data-id");
            const details = orderDetails[orderId] || [];
            orderDetailsBody.innerHTML = details
                .map(
                    (detail) => `
                <tr>
                    <td>${detail.detailId}</td>
                    <td>${orderId}</td>
                    <td>${detail.productId}</td>
                    <td>${detail.quantity}</td>
                    <td>${detail.price}</td>
                </tr>
            `
                )
                .join("");
        }

        // Handle "Done" button click
        if (e.target.classList.contains("mark-done")) {
            e.target.closest("tr").querySelector("td:nth-child(3)").textContent = "Completed";
            e.target.remove(); // Remove "Done" button
        }
    });
});
