document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const errorMessage = document.getElementById('error-message');
    
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(registerForm);
        
        fetch('../php/register_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '../login.html';
            } else {
                errorMessage.innerHTML = `<p class="error">${data.message}</p>`;
            }
        })
        .catch(error => {
            errorMessage.innerHTML = '<p class="error">An error occurred. Please try again.</p>';
            console.error('Error:', error);
        });
    });
});
