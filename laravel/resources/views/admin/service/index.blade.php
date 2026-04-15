@extends('layouts.admin')

    @section('title', 'Dịch vụ')

    @section('content_header_subtitle', 'Dịch vụ')
    @section('content')
        <div class="mb-3">
            <a href="{{ route('admin.service.create') }}" class="bg-blue text-white px-4 py-2 rounded">
                Thêm dịch vụ
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="service-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Trạng thái</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>
                                    @if ($service->image)
                                        <img src="{{ Storage::url($service->image) }}" width="60" height="60"
                                            style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>{{ $service->title }}</td>
                                <td>{{ $service->status ? 'Đã đăng' : 'Chưa đăng' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.service.edit', $service) }}" class="text-primary mr-2" title="Sửa"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="#" class="text-danger delete-btn"
                                        data-url="{{ route('admin.service.destroy', $service) }}"
                                        data-name="{{ $service->title }}" title="Xóa"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <x-common.delete-modal />
    @stop

    @section('css')
    @stop

    @section('js')
        <script src="{{ asset('js/datatable-config.js') }}"></script>
        <script>
            $(document).ready(function() {
                initDataTable('#service-table', [
                    { targets: 0, width: '15%' },
                    { targets: 1, width: '55%' },
                    { targets: 2, width: '15%' },
                    { targets: 3, width: '15%', orderable: false, searchable: false }
                ]);
            });
        </script>
    @stop

