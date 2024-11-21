const users = {
    admin: { username: 'admin', password: 'ad2324', role: 'admin' },
    faculty: { username: 'faculty', password: 'fa890', role: 'faculty' }
};

document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');

    // Check user credentials
    if (username === users.admin.username && password === users.admin.password) {
        // Redirect to admin page
        window.location.href = 'admin.html'; // Replace with your actual admin page
    } else if (username === users.faculty.username && password === users.faculty.password) {
        // Redirect to faculty page
        window.location.href = 'faculty.html'; // Replace with your actual faculty page
    } else {
        errorMessage.textContent = 'Invalid username or password.';
    }
});