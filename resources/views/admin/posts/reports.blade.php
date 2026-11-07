@extends('layouts.app')
@section('title', 'Báo cáo bài đăng')

@section('content')
<h1 class="page-title">Báo cáo bài đăng</h1>
<form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Tất cả trạng thái</option>
        @foreach(['Chờ xử lý','Đã xử lý','Đã từ chối'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
        @endforeach
    </select>
</form>
<div class="card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Bài đăng</th><th>Người báo cáo</th><th>Lý do</th><th>Trạng thái</th><th></th></tr></thead><tbody>
@forelse($reports as $report)
<tr>
    <td><a href="{{ route('posts.show', $report->post) }}">{{ $report->post->Title ?? '-' }}</a></td>
    <td>{{ $report->user->FullName ?? '-' }}</td>
    <td>{{ $report->Reason }}</td>
    <td><span class="badge {{ $report->Status === 'Chờ xử lý' ? 'text-bg-warning' : ($report->Status === 'Đã xử lý' ? 'text-bg-success' : 'text-bg-secondary') }}">{{ $report->Status }}</span></td>
    <td>
        @if($report->Status === 'Chờ xử lý')
            <form method="POST" action="{{ route('admin.post-reports.resolve', $report) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-primary">Đã xử lý</button></form>
            <form method="POST" action="{{ route('admin.post-reports.reject', $report) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-secondary">Từ chối</button></form>
        @endif
    </td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">Chưa có báo cáo.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $reports->links() }}</div>
@endsection
