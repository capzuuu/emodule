<!-- Toggle Status Confirmation Modal -->
<div class="modal fade" id="statusConfirmModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="statusConfirmModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <div class="modal-header">
                <h5 class="modal-title" id="statusConfirmModalLabel">
                    Confirm Action
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <p id="statusConfirmText" class="mb-0"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    Cancel
                </button>
                <button type="button" id="confirmStatusBtn" class="btn btn-primary">
                    Yes, Continue
                </button>
            </div>

        </div>
    </div>
</div>
<script nonce="<?= csp_nonce() ?>">
    let selectedBtn = null;

    $(document).on('click', '.toggle-status-btn', function() {

        selectedBtn = $(this);

        const table = $('#usersTable').DataTable();

        let tr = selectedBtn.closest('tr');

        // Handle DataTables child row
        if (tr.hasClass('child')) {
            tr = tr.prev();
        }

        const rowData = table.row(tr).data();

        if (!rowData) {
            console.error("Row data not found. Check DataTables responsive settings.");
            return;
        }

        const name = rowData.full_name;
        const status = selectedBtn.data('status');

        $('#statusConfirmText').html(
            status === 'active' ?
            `Are you sure you want to <strong>Deactivate</strong><br><strong>${name}</strong>?` :
            `Are you sure you want to <strong>Activate</strong><br><strong>${name}</strong>?`
        );

        $('#statusConfirmModal').modal('show');
    });

    $(document).on('click', '#confirmStatusBtn', function() {
        if (!selectedBtn) return;

        const $confirmBtn = $(this);
        $confirmBtn.html('<i class="bi bi-arrow-repeat me-1"></i> Processing...').prop('disabled', true);

        const userId = selectedBtn.data('id');
        const newStatus = selectedBtn.data('status') === 'active' ? 0 : 1;

        $.post('<?= baseurl("/admin/userAccounts/toggleStatus") ?>', {
            id: userId,
            status: newStatus
        }, function(res) {

            if (res.success) {

                if (newStatus === 1) {
                    selectedBtn
                        .removeClass('btn-warning')
                        .addClass('btn-success')
                        .data('status', 'active')
                        .html('<i class="bi bi-toggle-on"></i> Active');

                    notyf.success("User activated");

                } else {
                    selectedBtn
                        .removeClass('btn-success')
                        .addClass('btn-warning')
                        .data('status', 'inactive')
                        .html('<i class="bi bi-toggle-off"></i> Inactive');

                    notyf.error("User deactivated");
                }

            } else {
                notyf.error("Database update failed");
            }

            $confirmBtn.html('Yes, Continue').prop('disabled', false);
            setTimeout(function() { $('#statusConfirmModal').modal('hide'); }, 800);
        }, 'json');
    });
</script>