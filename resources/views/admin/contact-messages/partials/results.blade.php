<div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 40px;"></th>
                <th>From</th>
                <th>Subject</th>
                <th>Received</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $item)
                <tr class="{{ $item->is_read ? '' : 'table-success' }}">
                    <td class="text-center">
                        @if(! $item->is_read)
                            <span class="d-inline-block rounded-circle bg-success" style="width:8px;height:8px;" title="Unread"></span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $item->name }}</div>
                        <div class="small text-muted">{{ $item->email }}</div>
                        @if($item->phone)
                            <div class="small text-muted">{{ $item->phone }}</div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.contact-messages.show', $item) }}" class="text-decoration-none text-dark">
                            {{ $item->subject ?: 'No subject' }}
                        </a>
                        <div class="small text-muted text-truncate" style="max-width: 320px;">
                            {{ \Illuminate\Support\Str::limit($item->message, 80) }}
                        </div>
                    </td>
                    <td class="small text-muted text-nowrap">
                        {{ $item->created_at?->format('d M Y, h:i A') }}
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.contact-messages.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <form action="{{ route('admin.contact-messages.destroy', $item) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this message?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">No contact messages yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($messages->hasPages())
    <div class="card-footer border-top-0" id="messagePagination">
        {{ $messages->links() }}
    </div>
@endif
