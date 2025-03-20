<div class="card card-primary card-outline"
    style="border: 1px solid #ccc; padding: 10px; max-height: 80vh; overflow-y: auto;">
    <div class="card-body">
        <div style="border: 1px solid #ccc; padding: 15px;">
            @if ($contents->isNotEmpty())
                <h3 style="text-align: center; font-weight: bold; margin-bottom: 15px;">Text Content</h3>
                <ul style="list-style: none; padding: 0; font-size: 16px; margin: 0;">
                    @foreach ($contents as $content)
                        <li style="margin-bottom: 20px;  padding-bottom: 15px;">
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <strong style="font-size: 18px;">{{ $content->title }}</strong>
                                <p style="font-size: 16px; color: #333;">{{ $content->text }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="text-align: center; font-weight: bold; color: red;">No text content found</p>
            @endif
        </div>
    </div>
</div>
