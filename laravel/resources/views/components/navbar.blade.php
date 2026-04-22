<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand p-0" href="{{ route('home') }}">
            <img class="logo" src="{{ asset('images/common/logo.png') }}" alt="{{ config('app.name') }}"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="mainNav">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('home') ? ' active' : '' }}"
                        href="{{ route('home') }}"><span>Trang chủ</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('about') ? ' active' : '' }}"
                        href="{{ route('about') }}"><span>Giới thiệu</span></a>
                </li>
                <li class="nav-item dropdown">
                    <span class="position-relative d-block w-100">
                        <a class="nav-link dropdown-toggle flex-grow-1{{ request()->routeIs('product.*') ? ' active' : '' }}"
                            href="{{ route('product.index') }}" id="productsDropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <span>Sản phẩm</span>
                        </a>
                        <a class="font-weight-bold text-primary position-absolute d-lg-none small"
                            style="top:50%; right:12px; transform:translateY(-50%); text-decoration:none"
                            href="{{ route('product.index') }}">
                            <span>Xem thêm</span>
                        </a>
                    </span>
                    <div class="dropdown-menu{{ $nav_categories->count() > 10 ? ' dropdown-menu-multi' : '' }}" aria-labelledby="productsDropdown">
                        @foreach ($nav_categories->chunk(ceil($nav_categories->count() / 2)) as $chunk)
                            <ul class="list-unstyled dropdown-menu-col mb-0">
                                @foreach ($chunk as $cat)
                                    @php
                                        $activeSubCategories = $cat->subCategories->where('status', 1);
                                    @endphp

                                    @if ($activeSubCategories->isNotEmpty())
                                        <li class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle{{ request()->routeIs('product.category') && request()->route('category') === $cat->slug ? ' active' : '' }}"
                                                href="{{ route('product.category', ['category' => $cat->slug]) }}">
                                                <span>{{ $cat->name }}</span>
                                            </a>
                                            <div class="dropdown-menu">
                                                @foreach ($activeSubCategories as $sub)
                                                    <a class="dropdown-item{{ request()->routeIs('product.category') && request()->route('category') === $cat->slug && request()->route('subCategory') === $sub->slug ? ' active' : '' }}"
                                                        href="{{ route('product.category', ['category' => $cat->slug, 'subCategory' => $sub->slug]) }}">
                                                        <span>{{ $sub->name }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item{{ request()->routeIs('product.category') && request()->route('category') === $cat->slug ? ' active' : '' }}"
                                                href="{{ route('product.category', ['category' => $cat->slug]) }}">
                                                <span>{{ $cat->name }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('news.*') ? ' active' : '' }}"
                        href="{{ route('news.index') }}"><span>Tin tức</span></a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('service.*') ? ' active' : '' }}"
                        href="{{ route('service.index') }}"><span>Dịch vụ</span></a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('contact') ? ' active' : '' }}"
                        href="{{ route('contact') }}"><span>Liên hệ</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
