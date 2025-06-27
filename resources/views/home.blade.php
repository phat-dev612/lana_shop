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
    <!-- Danh mục sản phẩm -->
    <h2 class="mb-4 text-center">Danh mục sản phẩm</h2>
    @if ($categories->count() <= 10)
    <div class="row g-4 justify-content-center">
        @foreach($categories as $category)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('customer.category', $category->slug) }}" class="text-decoration-none">
                            <h6 class="card-title text-primary fw-bold mb-0">{{ $category->name }}</h6>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="row g-4 justify-content-center">
        <div class="col-12">
            <div class="row flex-nowrap overflow-auto" style="scrollbar-width: thin;">
                @foreach($categories as $category)
                    <div class="col-md-3 flex-shrink-0" style="min-width: 200px;">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <a href="{{ route('customer.category', $category->slug) }}" class="text-decoration-none">
                                    <h6 class="card-title text-primary fw-bold mb-0">{{ $category->name }}</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <!-- sản phẩm nổi bật -->
    <h2 class="mb-4 text-center">Sản phẩm nổi bật</h2>
    <div class="row g-4 justify-content-center">
        @forelse($featuredProducts ?? [] as $product)
            <x-product-card :product="$product" />
        @empty
           <!-- không có sản phẩm nổi bật -->
           <div class="col-12">
            <div class="alert alert-info">
                Không có sản phẩm nổi bật.
            </div>
           </div>
            
        @endforelse
    </div>
</div>
@endsection
