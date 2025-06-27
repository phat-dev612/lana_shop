@props(['product'])

<div class="col-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm product-card">
        <!-- Product Image -->
        <div class="position-relative">
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                 class="card-img-top" 
                 alt="{{ $product->name }}"
                 style="height: 200px; object-fit: cover;">
            
            <!-- Badge for Preorder -->
            @if($product->is_preorder)
                <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">
                    Đặt trước
                </span>
            @endif
            
            <!-- Badge for Sold Out -->
            @if(!$product->is_active)
                <span class="position-absolute top-0 end-0 badge bg-danger m-2">
                    Ngừng kinh doanh
                </span>
            @endif
        </div>
        
        <div class="card-body d-flex flex-column">
            <!-- Category -->
            @if($product->category)
                <small class="text-muted mb-1">{{ $product->category->name }}</small>
            @endif
            
            <!-- Product Name -->
            <h6 class="card-title fw-bold mb-2" title="{{ $product->name }}">
                {{ $product->name }}
            </h6>
            
            <!-- Description -->
            <p class="card-text small text-muted mb-3 flex-grow-1">
                {{ Str::limit($product->description, 80) }}
            </p>
            
            <!-- Price and Sold Info -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="fw-bold text-primary fs-6">
                        {{ number_format($product->price, 0, ',', '.') }} ₫
                    </span>
                </div>
                @if($product->sold > 0)
                    <small class="text-muted">
                        Đã bán: {{ $product->sold }}
                    </small>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div class="d-grid gap-2">
                <a href="{{ route('customer.product', $product->id) }}" 
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                </a>
                
                @auth
                    @if($product->is_active)
                        <button class="btn btn-primary btn-sm add-to-cart-btn" 
                                data-product-id="{{ $product->id }}">
                            <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                        </button>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-cart-x me-1"></i>Hết hàng
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product card script loaded');
    
    // Xử lý nút thêm vào giỏ hàng
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add to cart button clicked');
            
            const productId = this.dataset.productId;
            const originalText = this.innerHTML;
            
            console.log('Product ID:', productId);
            
            // Disable button và thay đổi text
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang thêm...';
            
            fetch('{{ route("customer.cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    // Hiển thị thông báo thành công
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Đã thêm';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    // Hiển thị thông báo toast
                    showToast('Sản phẩm đã được thêm vào giỏ hàng', 'success');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                showToast('Có lỗi xảy ra khi thêm sản phẩm', 'error');
            });
        });
    });
});

// Hàm hiển thị toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);

    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endauth 