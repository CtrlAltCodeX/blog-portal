<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Approval Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="approvalForm" method="POST">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="type" id="itemType">
                    <input type="hidden" name="id" id="itemId">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label>Status *</label>
                            <select name="status" id="statusSelect" class="form-control" required>
                                <option value="approved">Approved</option>
                                <option value="denied">Denied</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label>Cause of Rejection</label>
                            <textarea name="rejection_cause" id="rejectionCause" class="form-control" disabled
                                placeholder="Enter reason if denied"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label>Work Type</label>
                            <select name="worktype_id" id="worktypeSelect" class="form-control">
                                 <option value="">Select Work Type</option>
                                @foreach($worktypes as $w)
                                <option value="{{ $w->id }}" data-amount="{{ $w->amount }}">
                                    {{ $w->cause }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Expected Amount</label>
                            <input type="number" id="expectedAmount" name="expected_amount"
                                class="form-control" readonly>
                        </div>

                        <div class="col-md-12">
                            <label>Content Report Self Note</label>
                            <textarea name="content_report_note" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Host Records Self Notes</label>
                            <textarea name="host_record_note" class="form-control"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Submit Approval</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.getElementById("worktypeSelect").addEventListener("change", function() {
        let amount = this.selectedOptions[0].getAttribute("data-amount");
        document.getElementById("expectedAmount").value = amount;
    });

    document.getElementById("statusSelect").addEventListener("change", function() {
        if (this.value === "denied") {
            document.getElementById("rejectionCause").disabled = false;
        } else {
            document.getElementById("rejectionCause").disabled = true;
        }
    });
</script>