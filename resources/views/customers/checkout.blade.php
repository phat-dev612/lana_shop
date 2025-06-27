@extends('layouts.customer')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">{{ $title }}</h1>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ route('customer.checkout.place-order') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Thông tin sản phẩm -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Sản phẩm đã chọn
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($formattedCartItems as $item)
                                <div class="row align-items-center mb-3 pb-3 border-bottom">
                                    <div class="col-md-2">
                                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" 
                                             class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $item['name'] }}</h6>
                                        <small class="text-muted">{{ $item['category'] }}</small>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="text-muted">Số lượng: {{ $item['quantity'] }}</span>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <strong>{{ number_format($item['subtotal']) }} đ</strong>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Thông tin giao hàng -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2"></i>
                                    Thông tin giao hàng
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_name" class="form-label">Họ và tên *</label>
                                        <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" 
                                               id="shipping_name" name="shipping_name" 
                                               value="{{ old('shipping_name', $user->name) }}" required>
                                        @error('shipping_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_phone" class="form-label">Số điện thoại *</label>
                                        <input type="text" class="form-control @error('shipping_phone') is-invalid @enderror" 
                                               id="shipping_phone" name="shipping_phone" 
                                               value="{{ old('shipping_phone', $user->phone) }}" required>
                                        @error('shipping_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Địa chỉ giao hàng *</label>
                                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                              id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', $user->address) }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Phương thức thanh toán
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_cod" value="cod" {{ old('payment_method') == 'cod' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="payment_cod">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_bank" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_bank">
                                        <i class="fas fa-university me-2"></i>
                                        Chuyển khoản ngân hàng
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_cash" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_cash">
                                        <i class="fas fa-cash-register me-2"></i>
                                        Tiền mặt tại cửa hàng
                                    </label>
                                </div>
                                @error('payment_method')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Ghi chú đơn hàng
                                </h5>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Ghi chú về đơn hàng (không bắt buộc)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tóm tắt đơn hàng -->
                    <div class="col-lg-4">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    Tóm tắt đơn hàng
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($subtotal) }} đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span>{{ number_format($shippingFee) }} đ</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Tổng cộng:</strong>
                                    <strong class="text-primary fs-5">{{ number_format($total) }} đ</strong>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="fas fa-check me-2"></i>
                                    Đặt hàng
                                </button>
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Quay lại giỏ hàng
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill shipping information from user profile
    const userPhone = '{{ $user->phone }}';
    const userAddress = '{{ $user->address }}';
    
    if (!document.getElementById('shipping_phone').value && userPhone) {
        document.getElementById('shipping_phone').value = userPhone;
    }
    
    if (!document.getElementById('shipping_address').value && userAddress) {
        document.getElementById('shipping_address').value = userAddress;
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
        }
    });
});
</script>
@endpush
@endsection
