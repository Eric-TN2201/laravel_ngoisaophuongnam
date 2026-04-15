@extends('layouts.admin')

    @section('title', 'Tin tức & sự kiện')

    @section('content_header_subtitle', 'Tin tức & sự kiện')
    @section('content')
        <div class="mb-3">
            <a href="{{ route('admin.news.create') }}" class="bg-blue text-white px-4 py-2 rounded">
                Thêm tin tức / sự kiện
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="news-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Banner</th>
                            <th>Tiêu đề</th>
                            <th>Địa điểm</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Trạng thái</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($newsItems as $item)
                            <tr>
                                <td>
                                    @if ($item->banner)
                                        <img src="{{ Storage::url($item->banner) }}" width="60" height="60"
                                            style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->address ?? '-' }}</td>
                                <td>{{ $item->time_start ? $item->time_start->format('Y-m-d H:i') : '-' }}</td>
                                <td>{{ $item->status ? 'Đã đăng' : 'Chưa đăng' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.news.edit', $item) }}" class="text-primary mr-2" title="Sửa"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="#" class="text-danger delete-btn"
                                        data-url="{{ route('admin.news.destroy', $item) }}"
                                        data-name="{{ $item->title }}" title="Xóa"><i class="fa fa-trash"></i></a>
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
                initDataTable('#news-table', [
                    { targets: 0, width: '12%' },
                    { targets: 1, width: '25%' },
                    { targets: 2, width: '20%' },
                    { targets: 3, width: '15%' },
                    { targets: 4, width: '10%' },
                    { targets: 5, width: '18%', orderable: false, searchable: false }
                ]);
            });
        </script>
    @stop

