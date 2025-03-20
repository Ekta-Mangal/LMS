<div class="card card-primary card-outline" style="border: 1px solid #ccc; padding: 10px;">
    <div class="card-body">
        <!-- Course Title -->
        <div
            style="text-align: center; font-size: 18px; font-weight: bold; padding: 10px; border: 1px solid #ccc; margin-bottom: 10px;">
            {{ $coursedetails->title }}
        </div>

        <!-- Course Details Button -->
        <div style="text-align: center; margin-bottom: 15px;">
            <button class="btn" style="background-color: #f8f0a1; border: none; padding: 8px 20px;">
                Course Details
            </button>
        </div>

        <!-- Modules Section -->
        <div style="border: 1px solid #ccc; padding: 10px;">
            <h5><b>Learning Modules</b></h5>
            <ul style="list-style: none; padding: 0;">
                @foreach ($moduletitles as $title)
                    <li><i class="fas fa-arrow-right"></i> {{ $title }}</li>
                @endforeach
            </ul>
        </div>

        <!--  Badge and Certificate Section -->
        <div style="margin-top: 15px;">
            <h5>
                <img src="{{ asset('images/' . $coursedetails->badge . '.png') }}"
                    style="width: 40px; vertical-align: middle; margin-right: 21px; margin-left: 13px;">
                <b>{{ $coursedetails->badge }} Badge on Completion</b>
            </h5>
        </div>
        <div style="margin-top: 15px;">
            <h5>
                <img src="{{ asset('images/' . $coursedetails->badge . '.svg') }}"
                    style="width: 66px; vertical-align: middle; margin-right: 10px;">
                <b>Certification of Completion</b>
            </h5>
        </div>



        <!-- Enroll Now Button (Only if user is not already enrolled) -->
        @if (!$alreadyEnrolled)
            <div style="text-align: right; margin-top: 15px;">
                <button onclick="enroll({{ $coursedetails->id }})" class="btn btn-primary"
                    style="padding: 10px 20px; font-size: 16px;">
                    Enroll Now !!
                </button>
            </div>
        @else
            <div style="text-align: right; margin-top: 15px;">
                <button class="btn btn-success" style="padding: 10px 20px; font-size: 16px;" disabled>
                    Already enrolled in this course
                </button>
            </div>
        @endif
    </div>
</div>
