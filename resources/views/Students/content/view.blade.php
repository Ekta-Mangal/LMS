{{-- <div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
            <li class="pt-2 px-5">
                <h3 class="card-title"><b>Module : {{ $module->id }} - {{ $module->title }}</b></h3>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="home" data-toggle="pill" href="#tab" role="tab"
                    aria-controls="tab" aria-selected="true" onclick="callTab('home', {{ $module->id }})">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pdf" data-toggle="pill" href="#tab" role="tab" aria-controls="tab"
                    aria-selected="false" onclick="callTab('pdf', {{ $module->id }})">PDF</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="video" data-toggle="pill" href="#tab" role="tab" aria-controls="tab"
                    aria-selected="false" onclick="callTab('video', {{ $module->id }})">Video/Audio Player</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="text" data-toggle="pill" href="#tab" role="tab" aria-controls="tab"
                    aria-selected="false" onclick="callTab('text', {{ $module->id }})">Manual Text</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-two-tabContent">
            <div class="tab-pane fade show active" id="tabData" role="tabpanel">
                <div class="row align-items-center" id="completionSection">
                    <div class="col">
                        <p style="font-size: 16px; margin-bottom: 0;">
                            <strong>Note:</strong> If Completed, Click on "Mark as Completed"
                        </p>
                    </div>
                    <div class="col-auto">
                        <button id="moduleComplete" onclick="completed({{ $moduleDetails->module_id }})"
                            class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;"
                            {{ empty($moduleDetails->module_id) ? 'disabled' : '' }}>
                            Mark As Completed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
            <li class="pt-2 px-5">
                <h3 class="card-title"><b>Module : {{ $module->id }} - {{ $module->title }}</b></h3>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="home" data-toggle="pill" href="#tabData" role="tab"
                    aria-controls="tabData" aria-selected="true" onclick="callTab('home', {{ $module->id }})">
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pdf" data-toggle="pill" href="#tabData" role="tab"
                    aria-controls="tabData" aria-selected="false" onclick="callTab('pdf', {{ $module->id }})">
                    PDF
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="video" data-toggle="pill" href="#tabData" role="tab"
                    aria-controls="tabData" aria-selected="false" onclick="callTab('video', {{ $module->id }})">
                    Video/Audio Player
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="text" data-toggle="pill" href="#tabData" role="tab"
                    aria-controls="tabData" aria-selected="false" onclick="callTab('text', {{ $module->id }})">
                    Manual Text
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-two-tabContent">
            <div class="tab-pane fade show active" id="tabData" role="tabpanel"></div>
        </div>
    </div>
</div>

<script>
    function callTab(id, moduleId) {
        $.ajax({
            type: "get",
            url: "{{ route('gettabdata') }}",
            data: {
                "id": id,
                "module_id": moduleId
            },
            success: function(data) {
                if (data.status) {
                    $('#tabData').html(data.html);
                } else {
                    toastr.error(data.message);
                    $('#tabData').html('<p>Error loading content.</p>');
                }
            },
            error: function(xhr) {
                console.error("AJAX Error:", xhr);
                toastr.error('An error occurred while fetching the data.');
                $('#tabData').html('<p>Error loading content.</p>');
            }
        });
    }

    $(document).ready(function() {
        callTab('home', {{ $module->id }});
    });
</script>

<script>
    function completed(id) {
        var button = $("#moduleComplete");
        button.prop('disabled', true);
        $.ajax({
            url: "{{ route('modulecompleted') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Module marked as completed. Waiting for approval.');
                } else {
                    toastr.error('Waiting for Approval');
                }
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                toastr.error('Something went wrong. Please try again.');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        });
    }
</script>
