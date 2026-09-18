@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">Manage inventory products.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Products</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="card p-4 border-light shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Product List</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-box-seam"></i> Create Product</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/'.$product->product_photo) }}" alt="Product Photo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light text-muted d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->category ? $product->category->category_name : '-' }}</td>
                    <td>Rp {{ number_format($product->product_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        {{-- konfirmasi dulu biar admin ga sengaja kepencet tombol hapus --}}
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
