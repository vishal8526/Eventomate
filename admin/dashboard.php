<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventomate - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .event-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .detail-item {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .detail-item label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #666;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .tabs {
            margin-bottom: 20px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <div>
                <a href="../api/logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
        </div>

        <div class="tabs">
            <button class="btn btn-primary" onclick="showTab('pending')">Pending Events</button>
            <button class="btn btn-success" onclick="showTab('approved')">Approved Events</button>
            <button class="btn btn-danger" onclick="showTab('rejected')">Rejected Events</button>
        </div>

        <div id="pending-tab" class="tab-content active">
            <h2>Pending Events</h2>
            <div id="pending-events"></div>
        </div>

        <div id="approved-tab" class="tab-content">
            <h2>Approved Events</h2>
            <div id="approved-events"></div>
        </div>

        <div id="rejected-tab" class="tab-content">
            <h2>Rejected Events</h2>
            <div id="rejected-events"></div>
        </div>
    </div>

    <script>
        // Check if user is admin
        fetch('../api/check_admin.php')
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    window.location.href = '../login.html';
                } else {
                    loadEvents('pending');
                }
            });

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(`${tabName}-tab`).classList.add('active');
            
            // Load events for the selected tab
            loadEvents(tabName);
        }

        function loadEvents(status) {
            fetch(`../api/get_events.php?status=${status}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById(`${status}-events`);
                    container.innerHTML = '';

                    if (data.status === 'success' && data.events.length > 0) {
                        data.events.forEach(event => {
                            const eventCard = createEventCard(event);
                            container.appendChild(eventCard);
                        });
                    } else {
                        container.innerHTML = `
                            <div class="alert alert-info">
                                No ${status} events found.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    document.getElementById(`${status}-events`).innerHTML = `
                        <div class="alert alert-danger">
                            Error loading events. Please try again later.
                        </div>
                    `;
                });
        }

        function createEventCard(event) {
            const card = document.createElement('div');
            card.className = 'event-card';
            
            const statusClass = {
                'pending': 'status-pending',
                'approved': 'status-approved',
                'rejected': 'status-rejected'
            }[event.status] || 'status-pending';

            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>${event.event_type}</h3>
                    <span class="status-badge ${statusClass}">${event.status}</span>
                </div>
                <div class="event-details">
                    <div class="detail-item">
                        <label>Event Date</label>
                        <div>${new Date(event.event_date).toLocaleDateString()}</div>
                    </div>
                    <div class="detail-item">
                        <label>Number of Guests</label>
                        <div>${event.guests}</div>
                    </div>
                    <div class="detail-item">
                        <label>Venue</label>
                        <div>${event.venue}</div>
                    </div>
                    <div class="detail-item">
                        <label>Booking Date</label>
                        <div>${new Date(event.created_at).toLocaleDateString()}</div>
                    </div>
                    <div class="detail-item">
                        <label>User</label>
                        <div>${event.user_name}</div>
                    </div>
                </div>
                ${event.message ? `
                    <div class="detail-item">
                        <label>Additional Requirements</label>
                        <div>${event.message}</div>
                    </div>
                ` : ''}
                ${event.status === 'pending' ? `
                    <div class="action-buttons">
                        <button class="btn btn-success" onclick="updateEventStatus(${event.id}, 'approved')">
                            Approve
                        </button>
                        <button class="btn btn-danger" onclick="updateEventStatus(${event.id}, 'rejected')">
                            Reject
                        </button>
                    </div>
                ` : ''}
            `;

            return card;
        }

        function updateEventStatus(eventId, status) {
            fetch('../api/update_event_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    event_id: eventId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Reload all tabs
                    loadEvents('pending');
                    loadEvents('approved');
                    loadEvents('rejected');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the event status.');
            });
        }
    </script>
</body>
</html> 