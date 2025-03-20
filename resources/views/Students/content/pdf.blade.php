<div class="card card-primary card-outline"
    style="border: 1px solid #ccc; padding: 10px; max-height: 80vh; overflow-y: auto;">
    <div class="card-body">
        {{-- <div style="border: 1px solid #ccc; padding: 15px;"> --}}
        @if ($contents->isNotEmpty())
            <h3 style="text-align: center; font-weight: bold; margin-bottom: 15px;">Uploaded PDFs</h3>
            <ul style="list-style: none; padding: 0; font-size: 16px; margin: 0;">
                @foreach ($contents as $content)
                    <li style="margin-bottom: 20px; padding-bottom: 15px;">
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <strong style="font-size: 18px;">{{ $content->title }}</strong>
                            <a href="{{ asset($content->path) }}" target="_blank" class="btn btn-primary"
                                style="width: 150px; text-align: center;">
                                View PDF
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="text-align: center;font-weight: bold; color: red; ">No PDFs found</p>
        @endif
        {{-- </div> --}}
    </div>
</div>
