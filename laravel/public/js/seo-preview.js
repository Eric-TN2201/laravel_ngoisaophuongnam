document.querySelectorAll('[data-seo-editor]').forEach(editor => {
    const form = editor.closest('form');
    let timer;
    let version = 0;
    const refresh = async currentVersion => {
        const content = form.querySelector('[name="description"]');
        const richText = content && window.tinymce?.get(content.id);
        const payload = {
            page_route: editor.dataset.pageRoute,
            name: form.querySelector('[name="name"], [name="title"]')?.value || '',
            description: richText ? richText.getContent() : content?.value || '',
            meta_title: editor.querySelector('[data-seo-title]').value,
            meta_description: editor.querySelector('[data-seo-description]').value,
        };
        try {
            const response = await fetch(editor.dataset.previewUrl, {
                method: 'POST', credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json', 'Accept': 'application/json',
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                },
                body: JSON.stringify(payload),
            });
            if (!response.ok) throw new Error('Preview unavailable');
            const seo = await response.json();
            if (currentVersion !== version) return;
            editor.querySelector('[data-seo-preview-title]').textContent = seo.title;
            editor.querySelector('[data-seo-preview-description]').textContent = seo.description;
            editor.querySelector('[data-seo-count]').textContent = seo.title_length + ' ký tự tiêu đề · ' + seo.description_length + ' ký tự mô tả hiển thị';
        } catch {
            if (currentVersion === version) {
                editor.querySelector('[data-seo-count]').textContent = 'Không thể cập nhật bản xem trước. Vui lòng thử lại.';
            }
        }
    };
    const schedule = event => {
        if (event?.target instanceof Element && !editor.contains(event.target)
            && !event.target.matches('[name="name"], [name="title"], [name="description"]')) return;
        clearTimeout(timer);
        const currentVersion = ++version;
        timer = setTimeout(() => refresh(currentVersion), 250);
    };
    form.addEventListener('input', schedule);
    // TinyMCE edits happen inside an iframe and do not bubble to the form.
    if (window.tinymce) {
        const attach = instance => instance.on('input change undo redo', schedule);
        window.tinymce.get().forEach(attach);
        window.tinymce.on('AddEditor', event => attach(event.editor));
    }
});
