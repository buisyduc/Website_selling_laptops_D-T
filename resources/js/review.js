document.addEventListener('DOMContentLoaded', function() {
    // Tải thêm đánh giá
    const loadMoreBtn = document.getElementById('load-more-reviews');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const btn = this;
            const spinner = btn.querySelector('.fa-spinner');
            const url = btn.dataset.url;
            const page = btn.dataset.page;
            
            spinner.classList.remove('d-none');
            btn.disabled = true;
            
            fetch(`${url}?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const reviewsContainer = document.getElementById('reviews-container');
                    
                    // Tạo DOM từ HTML trả về
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.data.map(review => `
                        <div class="card mb-4 review-item">
                            <!-- Render review item similar to partial -->
                        </div>
                    `).join(''), 'text/html');
                    
                    // Thêm vào container
                    doc.body.childNodes.forEach(node => {
                        reviewsContainer.appendChild(node);
                    });
                    
                    // Cập nhật page tiếp theo
                    if (data.meta.current_page < data.meta.last_page) {
                        btn.dataset.page = parseInt(page) + 1;
                        spinner.classList.add('d-none');
                        btn.disabled = false;
                    } else {
                        btn.remove();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    spinner.classList.add('d-none');
                    btn.disabled = false;
                });
        });
    }
    
    // Khởi tạo lightbox cho ảnh đánh giá
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Ảnh %1 của %2'
        });
    }
});