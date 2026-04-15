@extends('layouts.admin')

    @section('title', 'Sản phẩm')

    {{-- @section('content_header')
        <h1>Danh sách sản phẩm</h1>
    @stop --}}

    @section('content_header_subtitle', 'Sản phẩm')
    @section('content')
        <div class="mb-3">
            <a href="{{ route('admin.product.create') }}" class="bg-blue text-white px-4 py-2 rounded">
                Thêm sản phẩm
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="products-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Danh mục</th>
                            <th>Danh mục con</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    @if($product->thumbnail)
                                        <img src="{{ Storage::url($product->thumbnail) }}" width="60" height="60" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->subCategory->name ?? '-' }}</td>
                                <td>{{ number_format($product->price ?? 0, 0, ',', '.') }} đ</td>
                                <td>{{ $product->status ? 'Hoạt động' : 'Tạm dừng' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.product.edit', $product) }}" class="text-primary mr-2" title="Sửa"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="#" class="text-danger delete-btn" data-url="{{ route('admin.product.destroy', $product) }}"
                                        data-name="{{ $product->name }}" title="Xóa"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-common.delete-modal />
    @stop

    @section('css')
        {{-- Add here extra stylesheets --}}
    @stop

    @section('js')
        <script src="{{ asset('js/datatable-config.js') }}"></script>
        <script>
            $(document).ready(function() {
                initDataTable('#products-table', [
                    { targets: 0, width: '12%' },
                    { targets: 1, width: '20%' },
                    { targets: 2, width: '15%' },
                    { targets: 3, width: '20%' },
                    { targets: 4, width: '13%' },
                    { targets: 5, width: '10%' },
                    { targets: 6, width: '10%', orderable: false, searchable: false }
                ]);
            });
        </script>
    @stop

