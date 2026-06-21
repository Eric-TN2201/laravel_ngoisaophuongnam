@extends('layouts.admin')

    @section('title', 'Banner')

    @section('content_header_subtitle', 'Banner')

    @section('content')
        <div class="mb-3">
            <a href="{{ route('admin.banner.create') }}" class="bg-blue text-white px-4 py-2 rounded">
                Thêm banners
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="banner-table" class="table table-bordered">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Tên</th>
                            <th>URL</th>
                            <th>Hình ảnh</th>
                            <th>Trạng thái</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                {{-- <td>{{ $banner->id }}</td> --}}
                                <td>{{ $banner->title }}</td>
                                <td>
                                    @if ($banner->link)
                                        <a href="{{ $banner->link }}" target="_blank" rel="noopener noreferrer">
                                            {{ $banner->link }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><img src="{{ asset('storage/' . $banner->image) }}" width="100"></td>
                                <td>{{ $banner->status ? 'Hoạt động' : 'Tạm dừng' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.banner.edit', $banner) }}" class="text-primary mr-2" title="Sửa"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="#" class="text-danger delete-btn"
                                        data-url="{{ route('admin.banner.destroy', $banner) }}"
                                        data-name="{{ $banner->title }}" title="Xóa"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-common.delete-modal />
    @endsection

    @section('css')
        {{-- Add here extra stylesheets --}}
        {{-- <link rel="stylesheet" href="/css/app"> --}}
    @stop

    @section('js')
        <script src="{{ asset('js/datatable-config.js') }}"></script>
        <script>
            $(document).ready(function() {
                initDataTable('#banner-table', [
                    { targets: 0, width: '18%' },
                    { targets: 1, width: '32%' },
                    { targets: 2, width: '25%' },
                    { targets: 3, width: '15%' },
                    { targets: 4, width: '10%', orderable: false, searchable: false }
                ]);
            });
        </script>
    @stop
