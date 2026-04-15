/**
 * Shared DataTable configuration for admin pages.
 *
 * Usage:
 *   initDataTable('#table-id', [
 *       { targets: 0, width: '20%' },
 *       { targets: 1, width: '30%' },
 *       ...
 *   ]);
 */
var dtLanguageVi = {
    lengthMenu: "Hiển thị _MENU_ dòng",
    zeroRecords: "Không tìm thấy dữ liệu",
    info: "Hiển thị _START_ – _END_ của _TOTAL_ dòng",
    infoEmpty: "Không có dữ liệu",
    infoFiltered: "(lọc từ _MAX_ dòng)",
    search: "Tìm kiếm:",
    paginate: {
        previous: "Trước",
        next: "Sau"
    },
};

function initDataTable(selector, columnDefs) {
    $(selector).DataTable({
        responsive: true,
        autoWidth: false,
        scrollY: '60vh',
        scrollCollapse: true,
        language: dtLanguageVi,
        columnDefs: columnDefs || [],
    });
}

// Shared delete modal handler
$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    var name = $(this).data('name');

    $('#delete-item-name').text(name);
    $('#delete-form').attr('action', url);
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
});

$(document).on('click', '#deleteModal .btn-secondary', function (e) {
    var modalEl = document.getElementById('deleteModal');
    var instance = bootstrap.Modal.getInstance(modalEl);
    if (instance) {
        instance.hide();
    }
});
