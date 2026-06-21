@props([
    'mode' => 'modal',
    'modalId' => 'consultationModal',
    'title' => 'Tư vấn miễn phí',
    'cardClass' => '',
])

@php($errors = $errors ?? new \Illuminate\Support\ViewErrorBag())

@if ($mode === 'modal')
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog"
        aria-labelledby="{{ $modalId }}Label" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('consultation.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="consultation_form_source" value="modal">
                    <div class="modal-body">
                        @if (session('consultation_success') && session('consultation_success_source') === 'modal')
                            <div class="alert alert-success">{{ session('consultation_success') }}</div>
                        @endif

                        <div class="form-group">
                            <label for="consultation_name">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('consultation_name') is-invalid @enderror"
                                id="consultation_name" name="consultation_name" value="{{ old('consultation_name') }}"
                                required>
                            @error('consultation_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="consultation_phone">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('consultation_phone') is-invalid @enderror"
                                id="consultation_phone" name="consultation_phone" value="{{ old('consultation_phone') }}"
                                required>
                            @error('consultation_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="consultation_area">Khu vực <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('consultation_area') is-invalid @enderror"
                                id="consultation_area" name="consultation_area" value="{{ old('consultation_area') }}"
                                required>
                            @error('consultation_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="consultation_type">Tôi là <span class="text-danger">*</span></label>
                            <select class="form-control @error('consultation_type') is-invalid @enderror"
                                id="consultation_type" name="consultation_type" required>
                                <option value="">Chọn đối tượng</option>
                                <option value="ca_nhan" {{ old('consultation_type') === 'ca_nhan' ? 'selected' : '' }}>
                                    Cá nhân</option>
                                <option value="nha_vuon" {{ old('consultation_type') === 'nha_vuon' ? 'selected' : '' }}>
                                    Nhà vườn</option>
                                <option value="dai_ly" {{ old('consultation_type') === 'dai_ly' ? 'selected' : '' }}>
                                    Đại lý</option>
                            </select>
                            @error('consultation_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success consultation-submit-btn"
                            data-loading-text="Đang gửi...">Gửi tư vấn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="consultation-inline-card p-4 {{ $cardClass }}">
        {{-- <h3 class="consultation-title">{{ $title }}</h3> --}}
        @if (session('consultation_success') && session('consultation_success_source') === 'inline')
            <div class="alert alert-success">{{ session('consultation_success') }}</div>
        @endif

        <form action="{{ route('consultation.send') }}" method="POST">
            @csrf
            <input type="hidden" name="consultation_form_source" value="inline">
            <div class="form-group">
                <label for="consultation_name_inline">Tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('consultation_name') is-invalid @enderror"
                    id="consultation_name_inline" name="consultation_name" value="{{ old('consultation_name') }}" required>
                @error('consultation_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="consultation_phone_inline">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('consultation_phone') is-invalid @enderror"
                    id="consultation_phone_inline" name="consultation_phone" value="{{ old('consultation_phone') }}"
                    required>
                @error('consultation_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="consultation_area_inline">Khu vực <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('consultation_area') is-invalid @enderror"
                    id="consultation_area_inline" name="consultation_area" value="{{ old('consultation_area') }}" required>
                @error('consultation_area')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="consultation_type_inline">Tôi là <span class="text-danger">*</span></label>
                <select class="form-control @error('consultation_type') is-invalid @enderror" id="consultation_type_inline"
                    name="consultation_type" required>
                    <option value="">Chọn đối tượng</option>
                    <option value="ca_nhan" {{ old('consultation_type') === 'ca_nhan' ? 'selected' : '' }}>Cá nhân</option>
                    <option value="nha_vuon" {{ old('consultation_type') === 'nha_vuon' ? 'selected' : '' }}>Nhà vườn</option>
                    <option value="dai_ly" {{ old('consultation_type') === 'dai_ly' ? 'selected' : '' }}>Đại lý</option>
                </select>
                @error('consultation_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success mt-3 consultation-submit-btn" data-loading-text="Đang gửi...">
                Gửi tư vấn
            </button>
        </form>
    </div>
@endif
