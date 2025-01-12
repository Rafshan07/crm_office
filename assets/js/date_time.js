// Initialize Product Chart
const ctx = document.getElementById('productChart').getContext('2d');
const productChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5'],
        datasets: [{
            label: 'Sales',
            data: [12, 19, 3, 5, 2],
            backgroundColor: '#4caf50',
            borderColor: '#388e3c',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Initialize Calendar
$(document).ready(function() {
    $('#calendar').fullCalendar({
        events: [
            {
                title: 'Product Launch',
                start: '2025-01-10T10:00:00',
                end: '2025-01-10T12:00:00'
            },
            {
                title: 'Team Meeting',
                start: '2025-01-12T14:00:00',
                end: '2025-01-12T16:00:00'
            }
        ]
    });
});
