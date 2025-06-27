@extends('layouts.customer')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">{{ $title }}</h1>
            
            @if(empty($formattedCartItems))
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">Giỏ hàng trống</h3>
                    <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                    <a href="{{ route('customer.products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formattedCartItems as $item)
                                    <tr data-cart-id="{{ $item['id'] }}">
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input cart-checkbox" data-cart-id="{{ $item['id'] }}" {{ $item['is_buy'] ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" 
                                                     class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $item['name'] }}</h6>
                                                    <small class="text-muted">{{ $item['category'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item['price']) }} đ</td>
                                        <td>
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                        data-action="decrease" data-cart-id="{{ $item['id'] }}" type="button">-</button>
                                                <input type="number" class="form-control text-center quantity-input" 
                                                       value="{{ $item['quantity'] }}" min="1" data-cart-id="{{ $item['id'] }}">
                                                <button class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                        data-action="increase" data-cart-id="{{ $item['id'] }}" type="button">+</button>
                                            </div>
                                        </td>
                                        <td class="item-total">{{ number_format($item['price'] * $item['quantity']) }} đ</td>
                                        <td>
                                            <button class="btn btn-outline-danger btn-sm remove-item" 
                                                    data-cart-id="{{ $item['id'] }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('customer.products') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                            </a>
                            <div class="text-end">
                                <h5>Tổng cộng: <span id="cart-total">{{ number_format($total) }}</span> đ</h5>
                                <a href="{{ route('customer.checkout') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>Thanh toán
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page loaded');
    let countItem = $('.cart-checkbox').length;
    function updateIsBuy(cartIds, isBuy) {
        fetch('{{ route("customer.cart.update-is-buy") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                cart_ids: cartIds,
                is_buy: isBuy
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Update is_buy response:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    function checkCheckboxAll() {
        let countChecked = document.querySelectorAll('.cart-checkbox:checked').length;
        if (countChecked === countItem) {
            document.getElementById('selectAll').checked = true;
        } else {
            document.getElementById('selectAll').checked = false;
        }
    }
    checkCheckboxAll();
    // Xử lý checkbox select all
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        // get all product id in cart
        let cartIds = [];
        checkboxes.forEach(checkbox => {
            cartIds.push(checkbox.dataset.cartId);
        });
        // update is_buy of product in cart
        updateIsBuy(cartIds, this.checked);
    });
    // Xử lý checkbox select item
    document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
            checkCheckboxAll();
            // update is_buy of product in cart
            updateIsBuy([cartId], this.checked);
        });
    });

    // Xử lý nút tăng/giảm số lượng
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const action = this.dataset.action;
            const input = document.querySelector(`input[data-cart-id="${cartId}"]`);
            let quantity = parseInt(input.value);
            
            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }
            
            input.value = quantity;
            updateCartItem(cartId, quantity);
        });
    });

    // Xử lý input số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const quantity = parseInt(this.value);
            if (quantity < 1) {
                this.value = 1;
                return;
            }
            updateCartItem(cartId, quantity);
        });
    });

    // Xử lý nút xóa sản phẩm
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                removeCartItem(cartId);
            }
        });
    });

    // Hàm cập nhật số lượng sản phẩm
    function updateCartItem(cartId, quantity) {
        console.log('Updating cart item:', cartId, 'quantity:', quantity);
        
        fetch('{{ route("customer.cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                cart_id: cartId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Update response:', data);
            if (data.success) {
                // Cập nhật tổng tiền của item
                const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(/[^\d]/g, ''));
                const total = price * quantity;
                row.querySelector('.item-total').textContent = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
                
                // Cập nhật tổng tiền giỏ hàng
                updateCartTotal();
            } else {
                alert('Có lỗi xảy ra: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
        });
    }

    // Hàm xóa sản phẩm khỏi giỏ hàng
    function removeCartItem(cartId) {
        console.log('Removing cart item:', cartId);
        
        fetch('{{ route("customer.cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                cart_id: cartId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Remove response:', data);
            if (data.success) {
                // Xóa row khỏi bảng
                const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                row.remove();
                
                // Cập nhật tổng tiền giỏ hàng
                updateCartTotal();
                
                // Kiểm tra nếu giỏ hàng trống
                const tbody = document.querySelector('tbody');
                if (tbody.children.length === 0) {
                    location.reload(); // Reload để hiển thị thông báo giỏ hàng trống
                }
            } else {
                alert('Có lỗi xảy ra: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }

    // Hàm cập nhật tổng tiền giỏ hàng
    function updateCartTotal() {
        let total = 0;
        document.querySelectorAll('.item-total').forEach(element => {
            const itemTotal = parseFloat(element.textContent.replace(/[^\d]/g, ''));
            total += itemTotal;
        });
        document.getElementById('cart-total').textContent = new Intl.NumberFormat('vi-VN').format(total);
    }
});
</script>
@endpush
@endsection 