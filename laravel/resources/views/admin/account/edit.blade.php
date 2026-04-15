@extends('layouts.admin')

    @section('title', 'Tài khoản')

    @section('content_header_subtitle', 'Cài đặt tài khoản')
    @section('content')
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">Thông tin</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.account.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Tên tài khoản</label>
                                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name ?? '') }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 mt-3 mt-md-0">
                <div class="card">
                    <div class="card-header">Đổi mật khẩu</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.account.password') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="current_password">Mật khẩu hiện tại</label>
                                <input type="password" id="current_password" name="current_password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}">
                                @if ($errors->has('current_password'))
                                    <div class="invalid-feedback">{{ $errors->first('current_password') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Mật khẩu mới</label>
                                <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('js')
        <script>
            @if (session('success'))
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: 'Thành công',
                    body: '{{ session('success') }}',
                    autohide: true,
                    delay: 2500
                })
            @endif

            @if (session('error'))
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Lỗi',
                    body: '{{ session('error') }}',
                    autohide: true,
                    delay: 3000
                })
            @endif
        </script>
    @stop

