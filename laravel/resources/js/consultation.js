document.addEventListener('DOMContentLoaded', () => {
    const consultationModal = document.getElementById('consultationModal');
    const consultationSuccessSource = document.body.dataset.consultationSuccessSource;

    document
        .querySelectorAll('form input[required], form select[required], form textarea[required]')
        .forEach((field) => {
            const label = document.querySelector(`label[for="${field.id}"]`);
            const fieldName = label
                ? label.textContent.replace('*', '').trim()
                : (field.getAttribute('name') || 'Trường này');

            const resetMessage = () => field.setCustomValidity('');

            field.addEventListener('invalid', () => {
                if (field.validity.valueMissing) {
                    field.setCustomValidity(`${fieldName} là bắt buộc.`);
                }
            });

            field.addEventListener('input', resetMessage);
            field.addEventListener('change', resetMessage);
        });

    const consultationForms = Array.from(
        document.querySelectorAll('input[name="consultation_form_source"]')
    )
        .map((input) => input.closest('form'))
        .filter((form, index, forms) => form && forms.indexOf(form) === index);

    consultationForms.forEach((form) => {
            form.addEventListener('submit', () => {
                const submitBtn = form.querySelector('.consultation-submit-btn');
                if (!submitBtn) {
                    return;
                }

                const loadingText = submitBtn.dataset.loadingText || 'Đang gửi...';
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>${loadingText}`;
            });
        });

    if (consultationSuccessSource === 'inline') {
        const consultantSection = document.getElementById('consultant-section');
        if (consultantSection) {
            const header = document.querySelector('header');
            const headerHeight = header ? header.getBoundingClientRect().height : 0;
            const top = consultantSection.getBoundingClientRect().top + window.pageYOffset - headerHeight - 12;

            window.scrollTo({
                top: Math.max(0, top),
                behavior: 'smooth',
            });
        }
    }

    if (!consultationModal) {
        return;
    }

    const hasConsultationErrors = document.body.dataset.consultationErrorsModal === '1';
    const hasConsultationSuccess = document.body.dataset.consultationSuccessModal === '1';

    if (!hasConsultationErrors && !hasConsultationSuccess) {
        return;
    }

    if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
        window.jQuery(consultationModal).modal('show');
    }
});
