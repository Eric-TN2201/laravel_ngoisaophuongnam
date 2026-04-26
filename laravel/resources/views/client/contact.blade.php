@extends('layouts.client')

@section('title', 'Liên hệ')

@section('content')
    <section class="mb-3 p-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
                </ol>
            </nav>

            <h2 class="h4 mb-0 text-center title-line mb-4">Liên hệ với chúng tôi</h2>

            {{-- Contact Info + Map --}}
            <div class="row mb-4">
                {{-- Google Map --}}
                <div class="col-lg-7">
                    @if (setting('company_address'))
                        <div class="map-container rounded overflow-hidden shadow-sm h-100">
                            <iframe
                                src="https://maps.google.com/maps/embed?pb={!! setting('company_address_pluscode') !!}"
                                width="100%" height="100%" style="border:0; min-height: 350px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif
                </div>

                <div class="col-lg-5 mb-4 mb-lg-0 mt-lg-0 mt-md-3">
                    <div class="contact-info-card p-4 h-100">
                        <h5 class="mb-4 font-weight-bold">{{ setting('company_name') }}</h5>

                        <div class="contact-item mb-3 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 contact-icon"></i>
                            <div>
                                <strong>Địa chỉ</strong><br>
                                {{ setting('company_address') }}
                            </div>
                        </div>

                        <div class="contact-item mb-3 d-flex align-items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3 contact-icon"></i>
                            <div>
                                <strong>Điện thoại</strong><br>
                                <a href="tel:{{ setting('company_phone') }}">{{ setting('company_phone') }}</a>
                            </div>
                        </div>

                        @if (setting('company_email'))
                            <div class="contact-item mb-3 d-flex align-items-start">
                                <i class="fas fa-envelope mt-1 mr-3 contact-icon"></i>
                                <div>
                                    <strong>Email</strong><br>
                                    <a href="mailto:{{ setting('company_email') }}">{{ setting('company_email') }}</a>
                                </div>
                            </div>
                        @endif

                        {{-- <hr> --}}

                        {{-- <h6 class="font-weight-bold mb-3">Kết nối với chúng tôi</h6>
                        <div class="d-flex">
                            @if (config('app.facebook') !== '#')
                                <a href="{{ config('app.facebook') }}" target="_blank" rel="noopener"
                                    class="contact-social-link mr-3" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if (config('app.zalo') !== '#')
                                <a href="{{ config('app.zalo') }}" target="_blank" rel="noopener"
                                    class="contact-social-link" title="Zalo">
                                    <i class="fas fa-comment-dots"></i>
                                </a>
                            @endif
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- Contact Form
            <h2 class="h4 mb-0 text-center title-line mb-4">Gửi tin nhắn cho chúng tôi</h2>
            <div class="contact-form-card p-4 mb-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                            name="subject" value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-common.button type="submit">
                        <i class="fas fa-paper-plane mr-1"></i> Gửi tin nhắn
                    </x-common.button>
                </form>
            </div> --}}
        </div>
    </section>

    <section id="consultant-section" class="mb-3 p-3">
        <div class="container-fix">
            <h2 class="h4 mb-0 text-center title-line mb-4">Tư vấn miễn phí</h2>
            <x-common.consultation-form mode="inline" title="Tư vấn miễn phí" />
        </div>
    </section>
@endsection
