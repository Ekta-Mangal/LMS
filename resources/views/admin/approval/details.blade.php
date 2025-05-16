<form action="{{ route('level_upgrade_accept') }}" method="post" id="UpgradeDetails" enctype="multipart/form-userDetails">
    @csrf
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="empid">Employee ID</label>
                        <input type="text" name="empid" id="empid" class="form-control"
                            value="{{ $userDetails->empid }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" class="form-control" value="{{ $userDetails->name }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" value="{{ $userDetails->location }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_name">Client</label>
                        <input type="text" class="form-control" value="{{ $userDetails->client_name }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="process">Process</label>
                        <input type="text" class="form-control" value="{{ $userDetails->process }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subprocess">Sub Process</label>
                        <input type="text" class="form-control" value="{{ $userDetails->subprocess }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="reporting_manager">Reporting Manager</label>
                        <input type="text" class="form-control" value="{{ $userDetails->reporting_manager }}"
                            readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="designation">Designation<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $userDetails->designation }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User Type<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" style="width: 100%;" name="role" id="role">
                            <option value="L1" {{ $userDetails->role == 'L1' ? 'selected' : '' }}>L1</option>
                            <option value="L2" {{ $userDetails->role == 'L2' ? 'selected' : '' }}>L2</option>
                            <option value="L3" {{ $userDetails->role == 'L3' ? 'selected' : '' }}>L3</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Badge Level<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" style="width: 100%;" name="badge_level"
                            id="badge_level">
                            <option value="Silver" {{ $userDetails->badge_level == 'Silver' ? 'selected' : '' }}>Silver
                            </option>
                            <option value="Gold" {{ $userDetails->badge_level == 'Gold' ? 'selected' : '' }}>Gold
                            </option>
                            <option value="Platinum" {{ $userDetails->badge_level == 'Platinum' ? 'selected' : '' }}>
                                Platinum
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $('#UpgradeDetails').validate({
            rules: {
                user_id: {
                    required: true
                },
                role: {
                    required: true
                },
                badge_level: {
                    required: true
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

<script>
    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });
</script>
