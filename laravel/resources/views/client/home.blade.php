@extends('layouts.client')

@section('title', 'Trang chủ')

@section('content')
    {{-- Slide Banners --}}
    <div class="swiper mb-4" id="mainBannerSwiper">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100" alt="Banner">
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>


    {{-- Sản phẩm của chúng tôi --}}
    <section class="mb-3 rounded">
        <div class="container-fix">
            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Sản phẩm xu hướng</h2>
            </div>
            <div class="swiper" id="productSwiper">
                <div class="swiper-wrapper py-3">
                    @foreach ($promoteProducts as $product)
                        <div class="swiper-slide">
                            <x-product.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>

    {{-- Video Banner --}}
    <section class="banner mb-3">
        <div class="ratio ratio-16x9" style="margin: 0 auto;">
            <video autoplay loop muted playsinline poster="{{ asset('images/video-banner-poster.jpg') }}"
                style="width:100%;height:auto;">
                <source src="{{ asset('images/Diet-sau-chan-dong.mp4') }}" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
        </div>
        </div>
    </section>

    {{-- Danh mục sản phẩm --}}
    <section class="mb-3">
        <div class="container-fix">
            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Phân loại sản phẩm</h2>
            </div>
            @foreach ($categories as $cat)
                @if ($cat->products->count())
                    <div class="rounded">
                        <div class="container-fix">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 mb-0">{{ $cat->name }}</h2>
                                <a href="{{ route('product.category', ['category' => $cat->slug]) }}"
                                    class="btn btn-link btn-sm">Xem tất cả</a>
                            </div>
                            <div class="row">
                                @foreach ($cat->products->take(8) as $product)
                                    <div class="col-6 col-md-4 col-lg-3 mb-3 px-2 fade-in-up">
                                        <div class="card h-100">
                                            {{-- {{ $product }} --}}
                                            <x-product.product-card :product="$product" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    {{-- Tin nổi bật --}}
    <section class="mb-3 rounded">
        <div class="container-fix">
            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Tin nổi bật</h2>
            </div>
            <div class="row">
                @foreach ($hotNews as $news)
                    <div class="mb-4 {{ $loop->index % 2 === 0 ? 'fade-in-left' : 'fade-in-right' }}">
                        {{-- {{ $loop->index}} --}}
                        <x-news.news-card :news='$news' :index='$loop->index' />
                    </div>
                    @if (!$loop->last)
                        <hr class="mb-4" style="border-top: 1px solid #b6b6b6; width: 60%; margin: 0 auto;">
                    @endif
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-2">
                <x-common.button onclick="window.location='{{ route('news.index') }}'">Xem thêm bài viết</x-common.button>
            </div>
        </div>
    </section>


    {{-- Về chúng tôi --}}
    <section id="story-session" class="p-3"
        style="background: url('{{ asset('images/about-us-bg-2.jpg') }}') center center/cover no-repeat; min-height:520px; display: flex; align-items: center;">
        <div class="story-form-bg">
            <div class="container-fix">
                <div class="d-flex flex-column flex-md-row gap-4">
                    {{-- Left: Header + Story --}}
                    <div style="flex: 1 1 50%; min-width: 0;">
                        <div class="text-center text-md-start mb-3">
                            <h2 class="h5 about-us-title rounded-pill mb-3 d-inline-block">VỀ CHÚNG TÔI</h2>
                        </div>
                        <div class="small mb-3">
                            {!! \Illuminate\Support\Str::limit(strip_tags($story), 800) !!}
                        </div>
                        <div class="d-flex justify-content-center">
                            <x-common.button onclick="window.location='{{ route('about') }}'">Xem chi tiết</x-common.button>
                        </div>
                    </div>

                    {{-- Right: Image Swiper --}}
                    <div class="img-swiper">
                        <div class="swiper" id="aboutSwiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/about-us-img-1.png') }}" alt="Về chúng tôi"
                                        class="rounded shadow w-100">
                                    <p class="text-center mt-2 fw-semibold small">Khu vực bao bì</p>
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/about-us-img-2.png') }}" alt="Về chúng tôi"
                                        class="rounded shadow w-100">
                                    <p class="text-center mt-2 fw-semibold small">Hình ảnh máy 1</p>
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/about-us-img-3.png') }}" alt="Về chúng tôi"
                                        class="rounded shadow w-100">
                                    <p class="text-center mt-2 fw-semibold small">Khu vực chai lọ</p>
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/about-us-img-4.png') }}" alt="Về chúng tôi"
                                        class="rounded shadow w-100">
                                    <p class="text-center mt-2 fw-semibold small">Hình ảnh máy 2</p>
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/about-us-img-5.png') }}" alt="Về chúng tôi"
                                        class="rounded shadow w-100">
                                    <p class="text-center mt-2 fw-semibold small">Hình ảnh máy 3</p>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section id="consultant-section" class="mb-3 p-3">
        <div class="container-fix">
            <h2 class="h4 mb-0 text-center title-line mb-4">Tư vấn miễn phí</h2>
            <x-common.consultation-form mode="inline" title="Tư vấn miễn phí" />
        </div>
    </section>

    <x-product.quick-view />
@endsection

@push('css')
@endpush

@push('scripts')
    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/quick-view.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Banner Swiper
            new Swiper('#mainBannerSwiper', {
                loop: true,
                pagination: {
                    el: '#mainBannerSwiper .swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '#mainBannerSwiper .swiper-button-next',
                    prevEl: '#mainBannerSwiper .swiper-button-prev'
                },
                autoplay: {
                    delay: 6000
                },
            });
            // Product Swiper
            new Swiper('#productSwiper', {
                loop: true,
                slidesPerView: 2,
                spaceBetween: 16,
                breakpoints: {
                    992: {
                        slidesPerView: 4
                    },
                    576: {
                        slidesPerView: 3
                    },
                },
                navigation: {
                    nextEl: '#productSwiper .swiper-button-next',
                    prevEl: '#productSwiper .swiper-button-prev'
                },
                pagination: false,
                // pagination: {
                //     el: '#productSwiper .swiper-pagination',
                //     clickable: true
                // },
            });
            // About Us Swiper
            new Swiper('#aboutSwiper', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 16,
                breakpoints: {
                    768: {
                        slidesPerView: 1
                    },
                    992: {
                        slidesPerView: 2
                    },
                },
                pagination: false,
                navigation: {
                    nextEl: '#aboutSwiper .swiper-button-next',
                    prevEl: '#aboutSwiper .swiper-button-prev'
                },
                autoplay: {
                    delay: 6000
                },
            });
            // Quick view
            // $(function() {
            //     // Ngăn button trigger card click
            //     document.querySelectorAll('.detail-view-btn').forEach(btn => {
            //         btn.addEventListener('click', function(e) {
            //             e.stopPropagation();
            //         });
            //     });
            //     $('.quick-view-btn').on('click', function() {
            //         $('#quickViewTitle').text($(this).data('name'));
            //         $('#quickViewCategory').text($(this).data('category'));
            //         $('#quickViewSubCategory').text($(this).data('subcategory'));
            //         $('#quickViewImage').attr('src', $(this).data('image'));
            //         $('#quickViewLink').attr('data-link', $(this).data('link'));
            //         $('#quickViewModal').modal('show');
            //     });
            // });
        });
    </script>
@endpush
