@extends('layouts.admin')

@section('title', 'Tạo đơn hàng mới')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Quản lý đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tạo đơn hàng mới</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle text-primary"></i> Tạo đơn hàng mới
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.store') }}" method="POST" id="createOrderForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Thông tin khách hàng -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Thông tin khách hàng</h5>
                                
                                <div class="form-group">
                                    <label for="user_id">Chọn khách hàng <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Chọn khách hàng</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name">Tên người đặt hàng <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                              rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Thông tin giao hàng -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Thông tin giao hàng</h5>
                                
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="copyCustomerInfo">
                                        <i class="fas fa-copy"></i> Copy thông tin khách hàng
                                    </button>
                                </div>
                                
                                <div class="form-group">
                                    <label for="shipping_name">Tên người nhận <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_name" id="shipping_name" class="form-control @error('shipping_name') is-invalid @enderror" 
                                           value="{{ old('shipping_name') }}" required>
                                    @error('shipping_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="shipping_phone">Số điện thoại người nhận <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_phone" id="shipping_phone" class="form-control @error('shipping_phone') is-invalid @enderror" 
                                           value="{{ old('shipping_phone') }}" required>
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="shipping_address">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                    <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" 
                                              rows="3" required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Phương thức thanh toán <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                        <option value="">Chọn phương thức thanh toán</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                                        <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Thanh toán online</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="shipping_fee">Phí vận chuyển <span class="text-danger">*</span></label>
                                    <input type="number" name="shipping_fee" id="shipping_fee" class="form-control @error('shipping_fee') is-invalid @enderror" 
                                           value="{{ old('shipping_fee', 0) }}" min="0" step="0.01" required>
                                    @error('shipping_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                              rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Sản phẩm đặt hàng</h5>
                                
                                <div id="orderItems">
                                    <div class="order-item border rounded p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sản phẩm</label>
                                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                                        <option value="">Chọn sản phẩm</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                                {{ $product->name }} - {{ number_format($product->price) }}đ
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Số lượng</label>
                                                    <input type="number" name="items[0][quantity]" class="form-control quantity-input" 
                                                           value="1" min="1" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Đơn giá</label>
                                                    <input type="text" class="form-control price-display" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success" id="addItem">
                                    <i class="fas fa-plus"></i> Thêm sản phẩm
                                </button>
                            </div>
                        </div>

                        <!-- Tổng tiền -->
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Tổng tiền</h6>
                                        <div class="row">
                                            <div class="col-6">Tổng tiền hàng:</div>
                                            <div class="col-6 text-right" id="subtotal">0đ</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">Phí vận chuyển:</div>
                                            <div class="col-6 text-right" id="shipping-fee-display">0đ</div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6"><strong>Tổng cộng:</strong></div>
                                            <div class="col-6 text-right"><strong id="total">0đ</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-save"></i> Tạo đơn hàng
                                </button>
                                <a href="{{ route('admin.orders') }}" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 0;

    // Thêm sản phẩm mới
    $('#addItem').click(function() {
        itemIndex++;
        const newItem = `
            <div class="order-item border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sản phẩm</label>
                            <select name="items[${itemIndex}][product_id]" class="form-control product-select" required>
                                <option value="">Chọn sản phẩm</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} - {{ number_format($product->price) }}đ
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số lượng</label>
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" 
                                   value="1" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Đơn giá</label>
                            <input type="text" class="form-control price-display" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#orderItems').append(newItem);
    });

    // Xóa sản phẩm
    $(document).on('click', '.remove-item', function() {
        if ($('.order-item').length > 1) {
            $(this).closest('.order-item').remove();
            calculateTotal();
        }
    });

    // Cập nhật giá khi chọn sản phẩm
    $(document).on('change', '.product-select', function() {
        const price = $(this).find(':selected').data('price');
        const priceDisplay = $(this).closest('.order-item').find('.price-display');
        priceDisplay.val(price ? price.toLocaleString() + 'đ' : '');
        calculateTotal();
    });

    // Cập nhật tổng tiền khi thay đổi số lượng
    $(document).on('input', '.quantity-input', function() {
        calculateTotal();
    });

    // Cập nhật tổng tiền khi thay đổi phí vận chuyển
    $('#shipping_fee').on('input', function() {
        calculateTotal();
    });

    // Tính tổng tiền
    function calculateTotal() {
        let subtotal = 0;
        
        $('.order-item').each(function() {
            const productSelect = $(this).find('.product-select');
            const quantityInput = $(this).find('.quantity-input');
            
            if (productSelect.val() && quantityInput.val()) {
                const price = productSelect.find(':selected').data('price');
                const quantity = parseInt(quantityInput.val());
                subtotal += price * quantity;
            }
        });

        const shippingFee = parseFloat($('#shipping_fee').val()) || 0;
        const total = subtotal + shippingFee;

        $('#subtotal').text(subtotal.toLocaleString() + 'đ');
        $('#shipping-fee-display').text(shippingFee.toLocaleString() + 'đ');
        $('#total').text(total.toLocaleString() + 'đ');
    }

    // Copy thông tin khách hàng sang thông tin giao hàng
    $('#user_id').change(function() {
        const userId = $(this).val();
        if (userId) {
            // Có thể thêm AJAX để lấy thông tin khách hàng
            // Hiện tại để admin tự nhập
        }
    });

    // Copy thông tin khách hàng sang thông tin giao hàng
    $('#copyCustomerInfo').click(function() {
        const name = $('#name').val();
        const phone = $('#phone').val();
        const address = $('#address').val();
        
        if (name && phone && address) {
            $('#shipping_name').val(name);
            $('#shipping_phone').val(phone);
            $('#shipping_address').val(address);
            
            // Hiển thị thông báo
            const alert = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                .html('Đã copy thông tin khách hàng sang thông tin giao hàng!')
                .append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
            
            $('.card-body').prepend(alert);
            
            // Tự động ẩn thông báo sau 3 giây
            setTimeout(function() {
                alert.alert('close');
            }, 3000);
        } else {
            // Hiển thị thông báo lỗi
            const alert = $('<div class="alert alert-warning alert-dismissible fade show" role="alert">')
                .html('Vui lòng điền đầy đủ thông tin khách hàng trước khi copy!')
                .append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
            
            $('.card-body').prepend(alert);
            
            // Tự động ẩn thông báo sau 3 giây
            setTimeout(function() {
                alert.alert('close');
            }, 3000);
        }
    });

    // Tính tổng tiền ban đầu
    calculateTotal();

    // Validation phía client
    $('#createOrderForm').on('submit', function(e) {
        let isValid = true;
        let errorMessage = '';

        // Kiểm tra thông tin khách hàng
        if (!$('#user_id').val()) {
            errorMessage += '- Vui lòng chọn khách hàng\n';
            isValid = false;
        }
        if (!$('#name').val()) {
            errorMessage += '- Vui lòng nhập tên người đặt hàng\n';
            isValid = false;
        }
        if (!$('#phone').val()) {
            errorMessage += '- Vui lòng nhập số điện thoại\n';
            isValid = false;
        }
        if (!$('#address').val()) {
            errorMessage += '- Vui lòng nhập địa chỉ\n';
            isValid = false;
        }

        // Kiểm tra thông tin giao hàng
        if (!$('#shipping_name').val()) {
            errorMessage += '- Vui lòng nhập tên người nhận\n';
            isValid = false;
        }
        if (!$('#shipping_phone').val()) {
            errorMessage += '- Vui lòng nhập số điện thoại người nhận\n';
            isValid = false;
        }
        if (!$('#shipping_address').val()) {
            errorMessage += '- Vui lòng nhập địa chỉ giao hàng\n';
            isValid = false;
        }

        // Kiểm tra phương thức thanh toán
        if (!$('#payment_method').val()) {
            errorMessage += '- Vui lòng chọn phương thức thanh toán\n';
            isValid = false;
        }

        // Kiểm tra sản phẩm
        let hasValidItems = false;
        $('.order-item').each(function() {
            const productId = $(this).find('.product-select').val();
            const quantity = $(this).find('.quantity-input').val();
            
            if (productId && quantity && quantity > 0) {
                hasValidItems = true;
            }
        });

        if (!hasValidItems) {
            errorMessage += '- Vui lòng thêm ít nhất một sản phẩm\n';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại thông tin:\n' + errorMessage);
            return false;
        }

        // Disable nút submit để tránh submit nhiều lần
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang tạo đơn hàng...');
    });
});
</script>
@endpush
@endsection 