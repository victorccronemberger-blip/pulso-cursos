<div class="modal  fade" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-16" id="ajaxModalLabel"></h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-br-cross-small text-20 text-white"></i></button>
            </div>
            <div class="modal-body">
                <div class="w-100 text-center py-5">
                    <div class="spinner-border my-5" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="eBtn gradient border-none" data-bs-dismiss="modal">{{ get_phrase('Fechar') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- video model --}}
<div class="modal  fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content video_contain">
            <div class="modal-header video_model">
                <h6 class="modal-title text-dark text-16px video_model_header" id="videoModalLabel"></h6>
                <button type="button" class="btn-close video_close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="w-100 text-center py-5">
                    <div class="spinner-border my-5" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal eModal fade" id="confirmModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered sweet-alerts text-sweet-alerts">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="icon icon-confirm">
                    <svg height="50px" width="50px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24" xml:space="preserve" fill="#ffffff" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <path style="fill:#ff3d3d;" d="M12,0C5.373,0,0,5.373,0,12s5.373,12,12,12s12-5.373,12-12S18.627,0,12,0z M12,19.66 c-0.938,0-1.58-0.723-1.58-1.66c0-0.964,0.669-1.66,1.58-1.66c0.963,0,1.58,0.696,1.58,1.66C13.58,18.938,12.963,19.66,12,19.66z M12.622,13.321c-0.239,0.815-0.992,0.829-1.243,0c-0.289-0.956-1.316-4.585-1.316-6.942c0-3.11,3.891-3.125,3.891,0 C13.953,8.75,12.871,12.473,12.622,13.321z"></path>
                            </g>
                        </g>
                    </svg>
                </div>
                <p class="title">{{ get_phrase('Tem certeza?') }}</p>
                <p class="focus-text">{{ get_phrase("Você não poderá desfazer esta ação!") }}</p>
                <div class="confirmBtn">
                    <button type="button" class="eBtn eBtn-red" data-bs-dismiss="modal">{{ get_phrase('Cancelar') }}</button>
                    <a href="" class="confirm-btn eBtn eBtn-green">{{ get_phrase("Sim, tenho certeza") }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="right-modal" class="modal fade" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-lg modal-right set-width">
        <div class="modal-content h-100">
            <div class="modal-header border-1">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<!-- Details Modal Start -->
<div id="tutor-service-modal" class="modal fade" tabindex="-1" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End -->

<script>
    "use strict";

    function showRightModal(url, header) {
        // SHOWING AJAX PRELOADER IMAGE
        jQuery('#right-modal .modal-body').html(
            '<div class="modal-spinner-border"><div class="spinner-border text-secondary" role="status"></div></div>'
        );
        jQuery('#right-modal .modal-title').html('...');
        // LOADING THE AJAX MODAL
        jQuery('#right-modal').modal('show', {
            backdrop: 'true'
        });

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: url,
            success: function(response) {
                jQuery('#right-modal .modal-title').html(header);
                jQuery('#right-modal .modal-body').html(response);

            }
        });
    }
</script>

<script type="text/javascript">
    "use strict";

    function ajaxModal(url, title, modalClasses = 'modal-md', animation = 'fade') {
        $('#ajaxModal .modal-dialog').removeClass('modal-sm');
        $('#ajaxModal .modal-dialog').removeClass('modal-md');
        $('#ajaxModal .modal-dialog').removeClass('modal-lg');
        $('#ajaxModal .modal-dialog').removeClass('modal-xl');
        $('#ajaxModal .modal-dialog').removeClass('modal-xxl');
        $('#ajaxModal .modal-dialog').removeClass('modal-fullscreen');
        $('#ajaxModal .modal-dialog').addClass(modalClasses);

        $('#ajaxModal').removeClass('fade');
        $('#ajaxModal').addClass(animation);

        $('#ajaxModal .modal-title').html(title);
        $("#ajaxModal").modal('show');
        $.ajax({
            type: 'get',
            url: url,
            success: function(response) {
                $('#ajaxModal .modal-body').html(response);
            }
        });
    }

    const myModalElModal = document.getElementById('ajaxModal')
    myModalElModal.addEventListener('hidden.bs.modal', event => {
        $('#ajaxModal .modal-body').html(
            '<div class="w-100 text-center py-5"><div class="spinner-border my-5" role="status"><span class="visually-hidden"></span></div></div>'
        );
    })


    function videoModal(url, title, modalClasses = 'modal-md', animation = 'fade') {
        $('#videoModal .modal-dialog').removeClass('modal-sm');
        $('#videoModal .modal-dialog').removeClass('modal-md');
        $('#videoModal .modal-dialog').removeClass('modal-lg');
        $('#videoModal .modal-dialog').removeClass('modal-xl');
        $('#videoModal .modal-dialog').removeClass('modal-xxl');
        $('#videoModal .modal-dialog').removeClass('modal-fullscreen');
        $('#videoModal .modal-dialog').addClass(modalClasses);

        $('#videoModal').removeClass('fade');
        $('#videoModal').addClass(animation);

        $('#videoModal .modal-title').html(title);
        $("#videoModal").modal('show');
        $.ajax({
            type: 'get',
            url: url,
            success: function(response) {
                $('#videoModal .modal-body').html(response);
            }
        });
    }

    const videoModalEl = document.getElementById('videoModal')
    videoModalEl.addEventListener('hidden.bs.modal', event => {
        $('#videoModal .modal-body').html(
            '<div class="w-100 text-center py-5"><div class="spinner-border my-5" role="status"><span class="visually-hidden"></span></div></div>'
        );
    })



    function confirmModal(url, elem = false, actionType = null, content = null) {
        $("#confirmModal").modal('show');

        if (elem != false) {
            $.ajax({
                url: url,
                success: function(response) {
                    response = JSON.parse(response);
                    //For redirect to another url
                    if (typeof response.success != "undefined") {
                        window.location.href = response.success;
                    }
                    distributeServerResponse(response);
                }
            });
        } else {
            $('#confirmModal .confirm-btn').attr('href', url);
            $('#confirmModal .confirm-btn').removeAttr('onclick');
        }
    }
</script>

<script>
    "use strict";

    function tutorServiceModal(url) {
        // SHOWING AJAX PRELOADER IMAGE
        jQuery('#tutor-service-modal .modal-body').html(
            '<div class="modal-spinner-border"><div class="spinner-border text-secondary" role="status"></div></div>'
        );
        // LOADING THE AJAX MODAL
        jQuery('#tutor-service-modal').modal('show', {
            backdrop: 'true'
        });

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: url,
            success: function(response) {
                jQuery('#tutor-service-modal .modal-body').html(response);

            }
        });
    }
</script>