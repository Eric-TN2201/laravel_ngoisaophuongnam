@extends('layouts.admin')

    @section('title', 'Danh mục')

    {{-- @section('content_header')
        <h1>Danh mục</h1>
    @stop --}}
    {{-- @section('content_header_title', 'Home') --}}
    @section('content_header_subtitle', 'Danh mục')
    @section('content')
        <div class=" mb-3">
            <a href="{{ route('admin.category.create') }}" class="bg-blue text-white px-4 py-2 rounded">
                Thêm Danh mục
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="users-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Chuỗi định danh</th>
                            <th>Trạng thái</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->status ? 'Hoạt động' : 'Tạm dừng' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.category.edit', $category) }}" class="text-primary mr-2" title="Sửa"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="#" class="text-danger delete-btn"
                                        data-url="{{ route('admin.category.destroy', $category) }}"
                                        data-name="{{ $category->name }}" title="Xóa"><i class="fa fa-trash"></i></a>
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
        {{-- <link rel="stylesheet" href="/css/app"> --}}
    @stop

    @section('js')
        <script src="{{ asset('js/datatable-config.js') }}"></script>
        <script>
            $(document).ready(function() {
                initDataTable('#users-table', [
                    { targets: 0, width: '35%' },
                    { targets: 1, width: '35%' },
                    { targets: 2, width: '20%' },
                    { targets: 3, width: '10%', orderable: false, searchable: false }
                ]);
            });
        </script>
    @stop

