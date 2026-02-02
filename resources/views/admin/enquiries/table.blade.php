<div class="table-responsive">
    <table class="table table-bordered text-nowrap border-bottom" id="candidateEnquiriesTable">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Batch Id</th>
                <th>Current Status</th>
                <th>Application Status</th>
                <th>Job type</th>
                <th>Candidate Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Year of Experience</th>
                <th>Previous Salary</th>
                <th>Notes</th>
                <th>Moved By</th>
                <th>Moved At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($enquiries as $enquiry)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $enquiry->batch_id }}</td>
                    <td>
                        @switch($enquiry->status)
                        @case('New Candidate')
                        <button class="btn-sm btn btn-warning">Pending</span>
                        @break;
                        @case('Successful Candidate')
                        <button class="btn-sm btn btn-success">Applied</span>
                        @break;
                        @case('Un-Successful Candidate')
                        <button class="btn-sm btn btn-danger">Not Applied</span>
                        @endswitch
                    </td>
                    <td>
                        @switch($enquiry->application_status)
                        @case(0)
                        <button class="btn-sm btn btn-danger">Pending</span>
                        @break;
                        @case(1)
                        <button class="btn-sm btn btn-success">Applied</span>
                        @endswitch
                    </td>
                    <td>{{ $enquiry->preference }}</td>
                    <td>{{ $enquiry->name }}</td>
                    <td>{{ $enquiry->age }}</td>
                    <td>{{ $enquiry->email }}</td>
                    <td>{{ $enquiry->phone }}</td>
                    <td>{{ $enquiry->address }}</td>
                    <td>{{ $enquiry->experience }} Years</td>
                    <td>₹{{ $enquiry->previous_salary }}</td>
                    <td>
                        <div class='{{ !$enquiry->notes ? "d-none" : '' }} add_notes'>
                            <textarea class='form-control' style='width:200px;' id='note-{{ $enquiry->id }}'>{{ $enquiry->notes }}</textarea>
                            <button class="btn btn-sm btn-primary mt-2 save_note" data-id="{{ $enquiry->id }}">Save/Update</button>
                        </div>
                        <button class="btn btn-sm btn-primary mt-2 add_note_btn {{ $enquiry->notes ? "d-none" : '' }}">Add Notes</button>
                    </td>
                    <td>
                        @if($enquiry->user_id)
                            {{ $enquiry->user->name??"N/A" }}
                        @elseif($enquiry->user_id == "0")
                            System
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $enquiry->updated_at }}</td>
                    <td>
                        @can('Job Enquiry List -> Action -> Solved Button')
                        <a href="javascript:void(0)"
                            class="btn btn-sm btn-success edit-enquiry"
                            data-id="{{ $enquiry->id }}"
                            data-status="{{ $enquiry->status }}">
                            <i class='fa fa-check'></i>
                        </a>
                        @endcan
                        @can('Job Enquiry List -> Action -> Delete Button')
                        <a href="#" class="btn btn-sm btn-danger">
                            <i class='fa fa-times'></i>    
                        </a>
                        @endcan
                    </td>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $enquiries->appends(request()->query())->links() }}
</div>

<div class="modal fade" id="editEnquiryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editEnquiryForm">
            @csrf
            <input type="hidden" name="id" id="edit_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Candidate Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="New Candidate">New Candidate</option>
                            @can('Job Enquiry -> Action -> Applied Successfully')
                            <option value="Successful Candidate">Successful Applied</option>
                            @endcan
                            @can('Job Enquiry -> Action -> Not Applied')
                            <option value="Un-Successful Candidate">Un-Successful</option>
                            @endcan
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>  

