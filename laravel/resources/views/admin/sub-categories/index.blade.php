@extends('layouts.admin')

    @section('title', 'Danh mục con')

    {{-- @section('content_header')
        <h1>Danh sách danh mục con</h1>
    @stop --}}

    @section('content_header_subtitle', 'Danh mục con')
    @section('content')
        <div class="mb-3">
            <a href="{{ route('admin.sub-category.create') }}" class="bg-blue text-white px-4 py-2 rounded">
                Thêm danh mục con
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="sub-categories-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Danh mục cha</th>
                            <th>Chuỗi định danh</th>
                            <th>Trạng thái</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subCategories as $subCategory)
                            <tr>
                                <td>{{ $subCategory->name }}</td>
                                <td>{{ $subCategory->category->name }}</td>
                                <td>{{ $subCategory->slug }}</td>
                                <td>{{ $subCategory->status ? 'Hoạt động' : 'Tạm dừng' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.sub-category.edit', $subCategory) }}" class="text-primary mr-2" title="Sửa"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="#" class="text-danger delete-btn" data-url="{{ route('admin.sub-category.destroy', $subCategory) }}"
                                        data-name="{{ $subCategory->name }}" title="Xóa"><i class="fa fa-trash"></i></a>
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
                initDataTable('#sub-categories-table', [
                    { targets: 0, width: '20%' },
                    { targets: 1, width: '20%' },
                    { targets: 2, width: '20%' },
                    { targets: 3, width: '15%' },
                    { targets: 4, width: '15%', orderable: false, searchable: false }
                ]);
            });
        </script>
    @stop

