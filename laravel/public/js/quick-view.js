document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.detail-view-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
            $('.quick-view-btn').on('click', function() {
                $('#quickViewTitle').text($(this).data('name'));
                $('#quickViewCategory').text($(this).data('category'));
                $('#quickViewSubCategory').text($(this).data('subcategory'));
                $('#quickViewImage').attr('src', $(this).data('image'));
                $('#quickViewLink').attr('data-link', $(this).data('link'));
                $('#quickViewDescription').text($(this).data('description'));
                $('#quickViewModal').modal('show');
            });
        });
