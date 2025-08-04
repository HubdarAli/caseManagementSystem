<div class="content py-3">
    <div class="row fs-sm">
        <div class="col-sm-12 order-sm-1 py-1 text-center text-sm-start">
            Copyright © {{ date('Y') }}, Sindh Flood Emergency Rehabilitation Project (Govt of Sindh)
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true"
    style="z-index: 1057">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger-light text-danger">
                <h5 class="modal-title" id="deleteModalLabel">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

            </div>

        </div>
    </div>
</div>


<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success-light text-success">
                <h5 class="modal-title" id="deleteModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

            </div>

        </div>
    </div>
</div>


{{-- media delete modal --}}
<div id="confirmationModal" class="modal fade" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteButton" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>



<!-- GT alert Modal after import-->
<div class="modal fade" id="gt-modal" tabindex="-1"
    aria-labelledby="GTModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning-light text-warning">
                <h5 class="modal-title" id="GTModalLabel">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if (Session::has('modal_data'))
                    @foreach (Session::get('modal_data') as $key => $item)
                        <div class="row mb-0">
                            <strong class="col-6 pl-1"> {{ Str::of($key)->replace('_', ' ')->ucfirst() }}
                            </strong>
                            <span class="col-6 pl-1">{{ $item }} </span>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>