@extends('layouts.admin')

    @section('title', 'Thông tin công ty')

    @section('content_header_subtitle', 'Thông tin công ty')
    @section('content')
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">Thông tin công ty</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.setting.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="company_name">Tên công ty</label>
                                <input type="text" id="company_name" name="company_name"
                                    class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_name', $settings['company_name'] ?? '') }}">
                                @if ($errors->has('company_name'))
                                    <div class="invalid-feedback">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="company_address">Địa chỉ</label>
                                <input type="text" id="company_address" name="company_address"
                                    class="form-control {{ $errors->has('company_address') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_address', $settings['company_address'] ?? '') }}">
                                @if ($errors->has('company_address'))
                                    <div class="invalid-feedback">{{ $errors->first('company_address') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="company_address_pluscode">Địa chỉ Google Maps (Plus Code)</label>
                                <input type="text" id="company_address_pluscode" name="company_address_pluscode"
                                    class="form-control {{ $errors->has('company_address_pluscode') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_address_pluscode', $settings['company_address_pluscode'] ?? '') }}">
                                @if ($errors->has('company_address_pluscode'))
                                    <div class="invalid-feedback">{{ $errors->first('company_address_pluscode') }}</div>
                                @endif
                                <small class="form-text text-muted">Dùng cho nhúng bản đồ Google Maps ở trang Liên hệ.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label for="company_phone">Số điện thoại</label>
                                <input type="text" id="company_phone" name="company_phone"
                                    class="form-control {{ $errors->has('company_phone') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_phone', $settings['company_phone'] ?? '') }}">
                                @if ($errors->has('company_phone'))
                                    <div class="invalid-feedback">{{ $errors->first('company_phone') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="company_email">Email</label>
                                <input type="email" id="company_email" name="company_email"
                                    class="form-control {{ $errors->has('company_email') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_email', $settings['company_email'] ?? '') }}">
                                @if ($errors->has('company_email'))
                                    <div class="invalid-feedback">{{ $errors->first('company_email') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="company_facebook">Facebook</label>
                                <input type="text" id="company_facebook" name="company_facebook"
                                    class="form-control {{ $errors->has('company_facebook') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_facebook', $settings['company_facebook'] ?? '') }}"
                                    placeholder="https://facebook.com/yourpage">
                                @if ($errors->has('company_facebook'))
                                    <div class="invalid-feedback">{{ $errors->first('company_facebook') }}</div>
                                @endif
                            </div>

                            <div class="form-group mt-3">
                                <label for="company_zalo">Zalo</label>
                                <input type="text" id="company_zalo" name="company_zalo"
                                    class="form-control {{ $errors->has('company_zalo') ? 'is-invalid' : '' }}"
                                    value="{{ old('company_zalo', $settings['company_zalo'] ?? '') }}"
                                    placeholder="https://zalo.me/0123456789">
                                @if ($errors->has('company_zalo'))
                                    <div class="invalid-feedback">{{ $errors->first('company_zalo') }}</div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary">Lưu thay đổi</button>
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

