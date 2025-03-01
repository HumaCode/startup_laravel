@props(['size' => 'lg', 'title', 'action' => null])

<div class="modal-dialog modal-dialog-scrollable modal-{{ $size }}" tabindex="-1" data-backdrop="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ $title }}</h5>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 hover-scale" data-bs-dismiss="modal"
                aria-label="Close">
                <i class="ki-duotone ki-cross fs-2x hover-scale"><span class="path1"></span><span
                        class="path2"></span></i>
            </div>
            <!--end::Close-->
        </div>

        <form id="form_action" action="{{ $action }}" method="POST">

            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                @csrf


                {{ $slot }}



            </div>

            @if ($action)
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger hover-scale" data-bs-dismiss="modal"><i
                            class="ki-outline ki-cross-circle fs-2"></i>&nbsp;BATAL</button>
                    <button type="submit" class="btn btn-primary hover-scale"><i
                            class="ki-outline ki-check-square fs-2"></i>&nbsp;
                        SIMPAN</button>
                </div>
            @endif
        </form>
    </div>
</div>
