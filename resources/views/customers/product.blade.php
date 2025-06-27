@extends('layouts.customer')

@section('title', $title)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.products') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <div class="product-image-container">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                     class="img-fluid rounded" style="max-height: 500px; object-fit: cover;">
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            <p class="text-muted mb-3">
                <i class="fas fa-tag me-2"></i>{{ $product->category->name }}
            </p>
            
            <div class="mb-4">
                <h3 class="text-primary mb-2">{{ number_format($product->price) }} đ</h3>
                @if($product->is_preorder)
                    <span class="badge bg-warning text-dark">Đặt trước</span>
                @endif
                @if($product->is_featured)
                    <span class="badge bg-success">Nổi bật</span>
                @endif
            </div>

            <div class="mb-4">
                <h5>Mô tả sản phẩm:</h5>
                <p>{{ $product->description }}</p>
            </div>

            @auth
            <div class="mb-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="quantity" class="form-label">Số lượng:</label>
                    </div>
                    <div class="col-auto">
                        <div class="input-group" style="width: 150px;">
                            <button class="btn btn-outline-secondary" type="button" id="decreaseQuantity">-</button>
                            <input type="number" class="form-control text-center" id="quantity" value="1" min="1">
                            <button class="btn btn-outline-secondary" type="button" id="increaseQuantity">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-lg" id="addToCartBtn" data-product-id="{{ $product->id }}">
                    <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                </button>
                
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để thêm sản phẩm vào giỏ hàng.
            </div>
            @endauth
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Sản phẩm liên quan</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $relatedProduct->image_url }}" class="card-img-top" 
                             alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                            <p class="card-text text-primary fw-bold">{{ number_format($relatedProduct->price) }} đ</p>
                            <a href="{{ route('customer.product', $relatedProduct->id) }}" class="btn btn-outline-primary btn-sm">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@auth
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product page loaded');
    
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    console.log('Elements found:', {
        quantityInput: !!quantityInput,
        decreaseBtn: !!decreaseBtn,
        increaseBtn: !!increaseBtn,
        addToCartBtn: !!addToCartBtn
    });

    // Xử lý nút tăng/giảm số lượng
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantityInput.value = quantity - 1;
            }
        });
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let quantity = parseInt(quantityInput.value);
            quantityInput.value = quantity + 1;
        });
    }

    // Xử lý input số lượng
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    }

    // Xử lý thêm vào giỏ hàng
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            console.log('Add to cart clicked');
            const productId = this.dataset.productId;
            const quantity = parseInt(quantityInput.value);
            
            console.log('Product ID:', productId, 'Quantity:', quantity);
            
            // Disable button để tránh click nhiều lần
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';

            fetch('{{ route("customer.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    // Hiển thị thông báo thành công
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    alert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>${data.success}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);

                    // Tự động ẩn sau 3 giây
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                } else {
                    throw new Error(data.error || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng: ' + error.message);
            })
            .finally(() => {
                // Enable lại button
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng';
            });
        });
    }
});
</script>
@endpush
@endauth
@endsection 