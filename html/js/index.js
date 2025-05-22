document.addEventListener('DOMContentLoaded', function() {
    const postsContainer = document.getElementById('posts-container');
    
    // Function to load posts
    function loadPosts() {
        fetch('../php/get_posts.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.posts.length > 0) {
                    let postsHTML = '';
                    data.posts.forEach(post => {
                        postsHTML += `
                            <div class="post">
                                <h3>${post.title}</h3>
                                <p>${post.content}</p>
                                <small>Posted on ${post.created_at}</small>
                            </div>
                        `;
                    });
                    postsContainer.innerHTML = postsHTML;
                } else {
                    postsContainer.innerHTML = '<p>No posts yet.</p>';
                }
            } else {
                postsContainer.innerHTML = `<p class="error">${data.message}</p>`;
            }
        })
        .catch(error => {
            postsContainer.innerHTML = '<p class="error">Failed to load posts. Please refresh the page.</p>';
            console.error('Error:', error);
        });
    }
    
    // Load posts when the page loads
    loadPosts();
});
