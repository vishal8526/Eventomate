document.getElementById('eventForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    // Get form values
    const eventType = document.getElementById('eventType').value;
    const eventDate = document.getElementById('eventDate').value;
    const venue = document.getElementById('venue').value;
    const guests = document.getElementById('guests').value;
    const budget = document.getElementById('budget').value;

    // Create summary text
    const summaryText = `
        <strong>Event Type:</strong> ${eventType}<br>
        <strong>Event Date:</strong> ${eventDate}<br>
        <strong>Venue:</strong> ${venue}<br>
        <strong>No. of Guests:</strong> ${guests}<br>
        <strong>Total Expected Budget:</strong> $${budget}
    `;

    // Display summary
    document.getElementById('summaryText').innerHTML = summaryText;

    // Clear form fields
    document.getElementById('eventForm').reset();
});