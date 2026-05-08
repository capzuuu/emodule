<div class="modal fade" id="changeCredentialsModal" tabindex="-1" aria-hidden="true" aria-labelledby="changeCredModalLabel">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <form id="changeCredentialsForm" method="POST" action="<?= baseurl('/admin/userAccounts/updateCredentials') ?>">
                <div class="modal-header bg-white text-dark">
                    <h5 class="modal-title" id="changeCredModalLabel">
                        <i class="bi bi-key-fill me-1"></i>
                        Change Credentials
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="cred_user_id">

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="cred_fullname" class="form-control text-muted" tabindex="-1" readonly>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password"
                            name="password"
                            id="cred_password"
                            class="form-control"
                            placeholder="Leave blank if no change"
                            autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password"
                            name="confirm"
                            id="cred_confirm"
                            class="form-control"
                            autocomplete="new-password">
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="showPasswords">
                        <label class="form-check-label" for="showPasswords">Show Password</label>
                    </div>

                    <small class="text-muted">
                        Password must be at least 8 characters.
                    </small>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="credSaveBtn" class="btn btn-primary" disabled>Update Credentials</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script nonce="<?= csp_nonce() ?>">
    $(document).ready(function() {

        const $credForm = $("#changeCredentialsForm");
        const $credPassword = $("#cred_password");
        const $credConfirm = $("#cred_confirm");
        const $credSaveBtn = $("#credSaveBtn");

        /* -------------------------------------------
           Enable button only when password is typed
        --------------------------------------------*/
        function checkCredentialInputs() {
            const hasPassword = $.trim($credPassword.val()) !== "";
            $credSaveBtn.prop("disabled", !hasPassword);
        }

        $credPassword.on("input", checkCredentialInputs);
        $credConfirm.on("input", checkCredentialInputs);

        /* -------------------------------------------
           Populate Modal
        --------------------------------------------*/
        $("#changeCredentialsModal").on("show.bs.modal", function(e) {
            const button = $(e.relatedTarget);

            $("#cred_user_id").val(button.data("id"));
            $("#cred_fullname").val(button.data("name"));

            $credPassword.val("");
            $credConfirm.val("");

            $credSaveBtn.prop("disabled", true);
        });

        /* -------------------------------------------
           Reset Modal
        --------------------------------------------*/
        $("#changeCredentialsModal").on("hidden.bs.modal", function() {
            $credForm[0].reset();
            $credSaveBtn.prop("disabled", true);
        });

        // Toggle visibility of both password fields
        $("#showPasswords").on("change", function() {
            const type = $(this).is(":checked") ? "text" : "password";
            $("#cred_password, #cred_confirm").attr("type", type);
        });

        // Reset to hidden when modal closes
        $("#changeCredentialsModal").on("hidden.bs.modal", function() {
            $("#showPasswords").prop("checked", false);
            $("#cred_password, #cred_confirm").attr("type", "password");
        });

        /* -------------------------------------------
           AJAX Submit (Same Flow as Edit User)
        --------------------------------------------*/
        $credForm.on("submit", function(e) {
            e.preventDefault();

            $credSaveBtn.html('<i class="bi bi-arrow-repeat me-1"></i> Updating...').prop("disabled", true);

            $.ajax({
                url: $credForm.attr("action"),
                method: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(res) {
                    if (res.status === "success") {
                        notyf.success(res.message);
                        $("#usersTable").DataTable().ajax.reload(null, false);
                        setTimeout(function() { $("#changeCredentialsModal").modal("hide"); }, 800);
                    } else {
                        notyf.error(res.message);
                        resetButton();
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    notyf.error("An error occurred while updating credentials.");
                    resetButton();
                }
            });

            function resetButton() {
                $credSaveBtn.html('Update Credentials').prop("disabled", false);
            }
        });

    });
</script>