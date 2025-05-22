document.addEventListener('DOMContentLoaded', function() {
    const postForm = document.getElementById('post-form');
    const errorMessage = document.getElementById('error-message');
    
    postForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(postForm);
        
        fetch('../php/create_post_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '../index.html';
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
