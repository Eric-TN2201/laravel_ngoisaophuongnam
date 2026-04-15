@csrf

<div class="form-group">
    <label for="product-category">Danh mục cha</label>
    <select id="product-category" name="category_id"
        class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}">
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ (int) old('category_id', $product->category_id ?? 0) === (int) $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}</option>
        @endforeach
    </select>

    @if ($errors->has('category_id'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('category_id') }}
        </div>
    @endif
</div>

<div class="form-group">
    <label for="product-sub-category">Danh mục con</label>
    <select id="product-sub-category" name="sub_category_id"
        class="form-control {{ $errors->has('sub_category_id') ? 'is-invalid' : '' }}">
        <option value="">-- Chọn danh mục con --</option>
        @foreach ($subCategories as $sub)
            <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}"
                {{ (int) old('sub_category_id', $product->sub_category_id ?? 0) === (int) $sub->id ? 'selected' : '' }}>
                {{ $sub->category->name }} → {{ $sub->name }}
            </option>
        @endforeach
    </select>

    @if ($errors->has('sub_category_id'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('sub_category_id') }}
        </div>
    @endif
</div>
<script>
    // Filter sub-categories by selected category
    (function() {
        var categorySelect = document.getElementById('product-category');
        var subSelect = document.getElementById('product-sub-category');
        if (!categorySelect || !subSelect) return;

        function filterSubs() {
            var cat = categorySelect.value;
            for (var i = 0; i < subSelect.options.length; i++) {
                var opt = subSelect.options[i];
                var optCat = opt.getAttribute('data-category');
                if (!opt.value) {
                    opt.style.display = '';
                    continue;
                }
                opt.style.display = (cat === '' || optCat === cat) ? '' : 'none';
            }
            // If selected option is hidden, clear selection
            if (subSelect.options[subSelect.selectedIndex] && subSelect.options[subSelect.selectedIndex].style
                .display === 'none') {
                subSelect.value = '';
            }
        }

        categorySelect.addEventListener('change', filterSubs);
        // init
        filterSubs();
        // when user picks a sub-category, set the parent category automatically
        subSelect.addEventListener('change', function() {
            var sel = subSelect.options[subSelect.selectedIndex];
            if (!sel) return;
            var selCat = sel.getAttribute('data-category');
            if (selCat) {
                categorySelect.value = selCat;
                // trigger filter to keep options consistent
                filterSubs();
            }
        });
    })();
</script>

<div class="form-group mt-3">
    <label for="product-name">Tên sản phẩm</label>
    <input type="text" id="product-name" name="name"
        class="form-control {{ $errors->has('name') || $errors->has('slug') ? 'is-invalid' : '' }}"
        value="{{ old('name', $product->name ?? '') }}">

    @if ($errors->has('name'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('name') }}
        </div>
    @endif

    @if ($errors->has('slug'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('slug') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="product-price-display">Giá (VND)</label>
    <input type="hidden" id="product-price" name="price" value="{{ old('price', $product->price ?? '') }}">
    <input type="text" id="product-price-display"
        class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
        value="{{ old('price', isset($product->price) ? number_format($product->price, 0, ',', '.') : '') }}"
        placeholder="0">

    @if ($errors->has('price'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('price') }}
        </div>
    @endif
</div>

<script>
    (function() {
        var display = document.getElementById('product-price-display');
        var hidden = document.getElementById('product-price');
        if (!display || !hidden) return;

        function parseVal(v) {
            if (!v) return '';
            return String(v).replace(/[^0-9]/g, '');
        }

        function formatVnd(v) {
            if (!v) return '';
            return v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // initialize hidden from display if needed
        if (!hidden.value && display.value) {
            hidden.value = parseVal(display.value);
        }
        if (display.value && !/\./.test(display.value)) {
            display.value = formatVnd(parseVal(display.value));
        }

        display.addEventListener('input', function() {
            hidden.value = parseVal(this.value);
        });
        display.addEventListener('blur', function() {
            this.value = formatVnd(parseVal(this.value));
        });
        display.addEventListener('focus', function() {
            this.value = parseVal(this.value);
        });

        // ensure form submit sends numeric value
        if (display.form) {
            display.form.addEventListener('submit', function() {
                hidden.value = parseVal(display.value);
            });
        }
    })();
</script>

<div class="form-group mt-3">
    <label for="product-description">Mô tả</label>
    <textarea id="product-description" name="description"
        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $product->description ?? '') }}</textarea>

    @if ($errors->has('description'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('description') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="product-status">Trạng thái</label>
    <select id="product-status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="1" {{ (string) old('status', $product->status ?? 1) === '1' ? 'selected' : '' }}>
            Hoạt động
        </option>
        <option value="0" {{ (string) old('status', $product->status ?? 1) === '0' ? 'selected' : '' }}>
            Tạm dừng
        </option>
    </select>

    @if ($errors->has('status'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('status') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="product-thumbnail">Ảnh đại diện</label>
    <input type="file" id="product-thumbnail" name="thumbnail"
        class="form-control {{ $errors->has('thumbnail') ? 'is-invalid' : '' }}" accept="image/*">

    @if (isset($product) && $product->thumbnail)
        <div class="mt-2">
            <img src="{{ Storage::url($product->thumbnail) }}" width="120" height="120"
                style="object-fit: cover;">
        </div>
    @endif

    @if ($errors->has('thumbnail'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('thumbnail') }}
        </div>
    @endif
</div>

<hr class="my-4">

<h5 class="mb-3">SEO</h5>

<div class="form-group">
    <label for="product-meta-title">Meta Title</label>
    <input type="text" id="product-meta-title" name="meta_title"
        class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}"
        value="{{ old('meta_title', $product->meta_title ?? '') }}">

    @if ($errors->has('meta_title'))
        <div class="invalid-feedback text-md">{{ $errors->first('meta_title') }}</div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="product-meta-description">Meta Description</label>
    <textarea id="product-meta-description" name="meta_description"
        class="form-control
        {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" rows="3">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>

    @if ($errors->has('meta_description'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('meta_description') }}
        </div>
    @endif
</div>

<div class="mt-4 mb-3">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.product.index') }}" class="text-primary ml-2">Trở lại</a>
</div>

<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script>
    tinymce.init({
        license_key: 'gpl',
        selector: '#product-description',
        plugins: 'autolink charmap codesample emoticons link lists searchreplace wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        height: 400,
        language: 'vi',
    });
</script>
