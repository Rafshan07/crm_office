function downloadInvoice() {
    const { jsPDF } = window.jspdf;
    let doc = new jsPDF();

    // Set Invoice Title
    doc.setFontSize(20);
    doc.text("APCORN INNOVATION", 15, 20);
    
    // Company Details
    doc.setFontSize(12);
    doc.text("Address: Eastern View (12th Floor), 50, Nayapaltan, Dhaka-1000", 15, 30);
    doc.text("Phone: +8801581-195132", 15, 40);
    doc.text("Email: accounts@apcorn.com", 15, 50);
    doc.text("Web: https://apcorn.com", 15, 60);

    // Invoice Title
    doc.setFontSize(18);
    doc.text("INVOICE", 150, 20);

    // Invoice Details
    doc.setFontSize(12);
    doc.text("Date: 29 Jul, 2024", 15, 75);
    doc.text("Invoice #: 202407118", 15, 85);

    // Client Details
    doc.text("TO:", 15, 100);
    doc.text("Md. Ismail Hossen", 15, 110);
    doc.text("Managing Director", 15, 120);
    doc.text("Eshan Overseas Ltd.", 15, 130);
    doc.text("50, Nayapaltan, Dhaka-1000", 15, 140);
    doc.text("+8801719-772489", 15, 150);

    // Table Header
    doc.setFontSize(12);
    doc.text("Qty", 15, 170);
    doc.text("Description", 40, 170);
    doc.text("Unit Price", 120, 170);
    doc.text("Line Total", 160, 170);

    // Table Content
    doc.text("1", 15, 180);
    doc.text("Website, Domain & Hosting", 40, 180);
    doc.text("BDT 17,000", 120, 180);
    doc.text("BDT 17,000", 160, 180);

    // Summary
    doc.text("Subtotal: BDT 17,000", 120, 200);
    doc.text("Sales Tax: 0", 120, 210);
    doc.text("Total: BDT 17,000", 120, 220);
    doc.text("Paid: 0", 120, 230);
    doc.text("Due: BDT 17,000", 120, 240);

    // Thank You Message
    doc.setFontSize(14);
    doc.text("Thank you for using our software!", 60, 270);

    // Save the PDF
    doc.save("Invoice_202407118.pdf");
}