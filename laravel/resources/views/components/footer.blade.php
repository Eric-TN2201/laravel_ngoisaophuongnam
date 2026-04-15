<footer class="bg-dark text-light py-5" style="font-size: 0.85rem;">
    <div class="container">
        <div class="row">
            <!-- Section 1: Brand Info -->
            <div class="col-md-5 mb-4">
                <div class="footer-section">
                    <h5 class="mb-3">{{ setting('company_name', config('app.name')) }}</h5>
                    <p class="mb-2">
                        <strong>Địa chỉ:</strong><br>
                        {{ setting('company_address', 'Địa chỉ công ty của bạn') }}
                    </p>
                    @if(setting('company_email'))
                    <p class="mb-2">
                        <strong>Email:</strong> {{ setting('company_email') }}
                    </p>
                    @endif
                    <p>
                        <strong>Điện thoại:</strong> {{ setting('company_phone', '+84 xxx xxx xxx') }}
                    </p>
                </div>
            </div>

            <!-- Section 2: Categories -->
            <div class="col-md-3 mb-4">
                <div class="footer-section">
                    <h5 class="mb-3">Danh mục sản phẩm</h5>
                    <ul class="list-unstyled">
                        @foreach ($nav_categories as $cat)
                            <li class="mb-2">
                                <a href="{{ route('product.category', ['category' => $cat->slug]) }}"
                                    class="text-light text-decoration-none" style="transition: color 0.3s;">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Section 3: Contact & Social -->
            <div class="col-md-4 mb-4">
                <div class="footer-section">
                    <h5 class="mb-3">Liên hệ & Theo dõi</h5>
                    <p class="mb-3">
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-sm">Liên hệ</a>
                    </p>
                    <div class="social-links">
                        <a href="{{ setting('company_facebook', '#') }}" class="text-light me-3 text-decoration-none"
                            title="Facebook" style="font-size: 1.5rem;">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="{{ setting('company_zalo', '#') }}" class="text-light text-decoration-none" title="Zalo"
                            style="font-size: 1.5rem;">
                            <i class="fab fa-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="bg-light">
        <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ setting('company_name', config('app.name')) }}. All rights reserved.</p>
        </div>
    </div>
</footer>
