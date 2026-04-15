<div id="quickViewModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="quickViewTitle"></h5>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <img id="quickViewImage" src="" class="img-fluid" alt="">
                    </div>
                    <div class="col-md-7">
                        <p><strong>Danh mục:</strong> <span id="quickViewCategory"></span></p>
                        <p><strong>Phân loại:</strong> <span id="quickViewSubCategory"></span></p>
                        <p id="quickViewDescriptionRow"><span id="quickViewDescription" class="text-muted"></span></p>
                        <x-common.button id="quickViewLink" onclick="window.location=this.getAttribute('data-link')">Xem
                            chi tiết</x-common.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
