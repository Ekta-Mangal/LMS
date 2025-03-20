<div class="card card-primary card-outline" style="border: 1px solid #ccc; padding: 10px;">
    <div class="card-body">
        <!-- Course Title -->
        <div
            style="text-align: center; font-size: 25px; font-weight: bold; padding: 20px; border: 1px solid #ccc; margin-bottom: 20px;">
            Level - 1 Course On QMS & Its Applications
        </div>

        <!-- Module Details Button -->
        <div style="text-align: center; margin-bottom: 22px;">
            <button class="btn" style="background-color: #f8f0a1; border: none; padding: 8px 20px;">
                Module Details
            </button>
        </div>

        <!-- Modules Section -->
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h5><b>Module - {{ $moduleDetails->id }} : {{ $moduleDetails->title }}</b></h5>
            <br>
            <ul style="list-style: none; padding: 0;font-size: 16px;">
                <li style="margin-bottom: 10px;"><strong>Created Date:</strong>
                    {{ $moduleDetails->created_at ?? 'N/A' }}
                </li>
            </ul>
            <br>
            <p style="font-size: 16px;"><strong>Note:</strong> If Completed, Click on
                "Mark as Completed" Button.
            </p>
        </div>

        <div style="text-align: right; margin-top: 15px;">
            @if ($userProgress->approval_status == 'Declined')
                <!-- Reattempt Button -->
                <button id="reattemptModule" onclick="reattemptmodule({{ $moduleDetails->id }})" class="btn btn-warning"
                    style="padding: 10px 20px; font-size: 16px;">
                    Reattempt
                </button>
            @elseif ($userProgress->approval_status == 'Pending')
                <!-- Start Module Button -->
                <button id="moduleStart" onclick="start({{ $moduleDetails->id }})" class="btn btn-primary"
                    style="padding: 10px 20px; font-size: 16px;">
                    Start Module
                </button>
            @endif
        </div>
    </div>
</div>
