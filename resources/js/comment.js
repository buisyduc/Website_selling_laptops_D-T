document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(commentForm);
            const loadingIndicator = document.getElementById('loading');
            const submitButton = commentForm.querySelector('button[type="submit"]');
            const productId = document.querySelector('input[name="post_id"]').value; // Lấy product ID từ hidden input
            
            // Hiển thị loading
            loadingIndicator.style.display = 'block';
            submitButton.disabled = true;
            
            // Sửa route thành products.comments.store
            fetch(`${productId}/comments`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Thêm comment mới vào đầu danh sách
                    const commentsList = document.getElementById('comments-list');
                    const newComment = document.createElement('div');
                    newComment.innerHTML = data.html;
                    commentsList.insertBefore(newComment.firstChild, commentsList.firstChild);
                    
                    // Reset form
                    document.getElementById('comment-content').value = '';
                    
                    // Thêm dòng này để chuyển hướng
                    window.location.href = data.redirect_url;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi bình luận: ' + error.message);
            })
            .finally(() => {
                loadingIndicator.style.display = 'none';
                submitButton.disabled = false;
            });
        });
    }
});