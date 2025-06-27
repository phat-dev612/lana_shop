@extends('layouts.customer')

@section('title', 'Products - Lana Shop')

@section('content')
<div class="container">
    <div class="row">
        <!-- Left Sidebar - Filters -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Categories</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('customer.products') }}" 
                               class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                All Categories
                            </a>
                            @foreach($categories as $category)
                            <a href="{{ route('customer.category', $category->slug) }}" 
                               class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                                {{ $category->name }}
                                <span class="badge bg-secondary float-end">{{ $category->products->count() }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Price Range</h6>
                        <form action="{{ route('customer.products') }}" method="GET">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="mb-2">
                                <label for="min_price" class="form-label">Min Price</label>
                                <input type="number" class="form-control form-control-sm" id="min_price" name="min_price" 
                                       value="{{ request('min_price') }}" min="0">
                            </div>
                            <div class="mb-2">
                                <label for="max_price" class="form-label">Max Price</label>
                                <input type="number" class="form-control form-control-sm" id="max_price" name="max_price" 
                                       value="{{ request('max_price') }}" min="0">
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">Apply Filter</button>
                        </form>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Sort By</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" 
                               class="list-group-item list-group-item-action {{ request('sort') == 'name_asc' ? 'active' : '' }}">
                                Name A-Z
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" 
                               class="list-group-item list-group-item-action {{ request('sort') == 'name_desc' ? 'active' : '' }}">
                                Name Z-A
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" 
                               class="list-group-item list-group-item-action {{ request('sort') == 'price_asc' ? 'active' : '' }}">
                                Price Low to High
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" 
                               class="list-group-item list-group-item-action {{ request('sort') == 'price_desc' ? 'active' : '' }}">
                                Price High to Low
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" 
                               class="list-group-item list-group-item-action {{ request('sort') == 'newest' ? 'active' : '' }}">
                                Newest First
                            </a>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if(request('category') || request('min_price') || request('max_price') || request('sort'))
                    <div class="mb-3">
                        <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-times me-1"></i>Clear All Filters
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content - Products -->
        <div class="col-lg-9 col-md-8">
            <!-- Header with search and results count -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ $title ?? 'Sản phẩm' }}</h4>
                    <p class="text-muted mb-0">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                        of {{ $products->total() }} products
                    </p>
                </div>
                
                <!-- Search Form -->
                <div class="d-flex">
                    <form action="{{ route('customer.search') }}" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control me-2" 
                               placeholder="Tìm kiếm sản phẩm..." 
                               value="{{ request('q') ?? '' }}"
                               style="min-width: 250px;">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">No products found</div>
            @endif
        </div>
        <!-- phân trang -->
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

