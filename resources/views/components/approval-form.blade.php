<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="approvalForm" method="POST">
                @csrf
                <input type="hidden" name="type" id="itemType">
                <input type="hidden" name="id" id="itemId">

                <div class="modal-body">

                    <label>Status *</label>
                    <select name="status" id="statusSelect" class="form-control" required>
                        <option value="approved">Approved</option>
                        <option value="denied">Denied</option>
                    </select>

                    <label class="mt-3">Cause of Rejection</label>
                    <textarea name="rejection_cause" id="rejectionCause" class="form-control" disabled
                        placeholder="Enter reason if denied"></textarea>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.getElementById("statusSelect").addEventListener("change", function() {
    document.getElementById("rejectionCause").disabled = this.value !== "denied";
});
</script>
