<div class="card"
    style="border: 1px solid #ccc; padding: 20px; text-align: center; background-color: #fff7d9; 
    max-width: 600px; margin: auto; border-radius: 20px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);">
    <div class="card-body">
        <p style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Hi {{ Auth::user()->name }}</p>

        <p style="font-size: 16px; font-weight: bold; color: #333;">You Have Failed Re-Attempt Test Too..</p>

        <p style="font-size: 14px; color: #333; line-height: 1.5;">
            You must complete your course from the beginning...
            All your progress of the course will be reset.
        </p>

        <!-- OK Button -->
        <button type="button" class="btn" id="restart"
            style="padding: 10px 20px; font-size: 16px; background-color: #73a6ea; 
            border: none; text-decoration: none; color: white; margin-top: 15px; border-radius: 5px;"
            onclick="restartCourse({{ $module_id }})">
            OK
        </button>
    </div>
</div>

<script>
    function restartCourse(id) {
        var button = document.getElementById("restart");
        button.disabled = true;
        button.innerHTML = "Processing...";
        $.ajax({
            url: "{{ route('restartcourse') }}",
            type: 'POST',
            data: {
                "module_id": id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = "{{ route('dashboard') }}"; // Redirect to dashboard
                    }, 2000);
                } else {
                    toastr.error(response.message);
                    button.disabled = false; // Re-enable button if error
                    button.innerHTML = "OK";
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                button.disabled = false; // Re-enable button if error
                button.innerHTML = "OK";
            }
        });
    }
</script>
