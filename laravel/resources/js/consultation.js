document.addEventListener('DOMContentLoaded', () => {
    const consultationModal = document.getElementById('consultationModal');

    if (!consultationModal) {
        return;
    }

    const hasConsultationErrors = document.body.dataset.consultationErrors === '1';
    const hasConsultationSuccess = document.body.dataset.consultationSuccess === '1';

    if (!hasConsultationErrors && !hasConsultationSuccess) {
        return;
    }

    if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
        window.jQuery(consultationModal).modal('show');
    }

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
});
