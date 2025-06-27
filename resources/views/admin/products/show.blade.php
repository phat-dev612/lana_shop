@extends('layouts.admin')

@section('title', 'Product Details - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Details</h1>
        <div>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Product
            </a>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm me-2" onclick="confirmDelete()">
                <i class="fas fa-trash fa-sm text-white-50"></i> Delete Product
            </button>
            <form id="delete-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
            <a href="{{ route('admin.products') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
            </a>
        </div>
    </div>

    <!-- Product Details Card -->
    <div class="row">
        <!-- Product Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted" style="width: 120px;">Product ID:</th>
                                    <td><strong>{{ $product->id }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Name:</th>
                                    <td><strong>{{ $product->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Slug:</th>
                                    <td><code>{{ $product->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Category:</th>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted" style="width: 120px;">Price:</th>
                                    <td><strong class="text-primary">{{ number_format($product->price, 2) }} VND</strong></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Sold:</th>
                                    <td><strong>{{ $product->sold }}</strong> units</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Pre-order:</th>
                                    <td>
                                        <span class="badge bg-{{ $product->is_preorder ? 'warning' : 'secondary' }}">
                                            {{ $product->is_preorder ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($product->description)
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-primary">Description</h6>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Timestamps</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted" style="width: 120px;">Created:</th>
                                    <td>{{ $product->created_at->format('F j, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Updated:</th>
                                    <td>{{ $product->updated_at->format('F j, Y \a\t g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Image -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Image</h6>
                </div>
                <div class="card-body text-center">
                    @if($product->image_url)
                        <img src="{{ \App\Helpers\ImageHelper::getProductThumbnail($product->image_url) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3" style="max-height: 300px;">
                        <div class="mt-2">
                            <a href="{{ $product->image_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> View Full Size
                            </a>
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" 
                             style="height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No image available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection
@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this product?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
