@extends('layouts.customer')
@section('title', 'Trang chủ')
@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-4 fw-bold mb-3">Chào mừng đến với Lana Shop</h1>
            <p class="lead mb-4">Nền tảng thương mại điện tử hiện đại, dễ sử dụng, bảo mật và tối ưu cho mọi thiết bị. Khám phá các sản phẩm chất lượng với giá tốt nhất!</p>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170576.png" alt="Lana Shop" class="img-fluid" style="max-height: 260px;">
        </div>
    </div>
    <hr>
    <h2 class="mb-4 text-center">Sản phẩm nổi bật</h2>
    <div class="row g-4 justify-content-center">
        <!-- Placeholder sản phẩm, sau này sẽ render động -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x200?text=Product+1" class="card-img-top" alt="Product 1">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm 1</h5>
                    <p class="card-text">Mô tả ngắn sản phẩm 1.</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x200?text=Product+2" class="card-img-top" alt="Product 2">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm 2</h5>
                    <p class="card-text">Mô tả ngắn sản phẩm 2.</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x200?text=Product+3" class="card-img-top" alt="Product 3">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm 3</h5>
                    <p class="card-text">Mô tả ngắn sản phẩm 3.</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
