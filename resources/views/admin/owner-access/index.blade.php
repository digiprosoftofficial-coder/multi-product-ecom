@extends('admin.layouts.master')

@section('title', 'Owner access')
@section('page-title', 'Owner access')

@section('content')
<p class="text-muted mb-4">Choose which extra admin screens the store owner can use. Themes stay super admin only.</p>

<form action="{{ route('admin.owner-access.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Can give to owner</h5>
        </div>
        <div class="card-body p-0">
            @foreach($features as $id => $feature)
                <div class="d-flex align-items-start justify-content-between gap-3 px-3 py-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                    <div>
                        <label class="form-check-label fw-semibold" for="feature_{{ $id }}">{{ $feature['label'] }}</label>
                        <div class="form-text mt-1">{{ $feature['help'] }}</div>
                    </div>
                    <div class="form-check form-switch m-0 flex-shrink-0">
                        <input type="hidden" name="{{ $id }}" value="0">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="feature_{{ $id }}" name="{{ $id }}" value="1"
                               {{ old($id, $feature['enabled'] ? '1' : '0') === '1' ? 'checked' : '' }}>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Always super admin only</h5>
        </div>
        <div class="card-body d-flex align-items-start justify-content-between gap-3">
            <div>
                <div class="fw-semibold">Themes</div>
                <div class="form-text mt-1">Preview, activate, and delete themes. The owner never sees this screen.</div>
            </div>
            <span class="badge text-bg-secondary">Locked</span>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save owner access</button>
</form>
@endsection
