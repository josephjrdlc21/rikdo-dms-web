@extends('pdf._layouts.main')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" class="border-white">
    <tbody>
        <tr class="border-none">
            <td class="text-center border-none">
                <p class="lh1 fs14 text-center"><b>Audit Trail Report</b></p>
                <p class="lh1 fs14 text-center"><b>{{Carbon::now()->format('F d, Y g:i A')}}</b></p>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellpadding="1" cellspacing="0">
    <thead>
        <tr>
            <th class="text-center text-uppercase fs14">User</th>
            <th class="text-center text-uppercase fs14">IP Address</th>
            <th class="text-center text-uppercase fs14">Process</th>
            <th class="text-center text-uppercase fs14">Type</th>
            <th class="text-center text-uppercase fs14">Actions</th>
            <th class="text-center text-uppercase fs14">Date Created</th>
        </tr>
    </thead>
    <tbody class="text-sm">
        @forelse($record as $index => $audit_trail)
        <tr>
            <td>{{$audit_trail->user->name}}<br><small>{{Helper::capitalize_text($audit_trail->user->user_info->role) ?? ''}}</small></td>
            <td>{{$audit_trail->ip}}</td>
            <td>{{str_replace('_', ' ', $audit_trail->process)}}</td>
            <td>{{str_replace('_', ' ', $audit_trail->type)}}</td>
            <td>{{$audit_trail->remarks}}</td>
            <td>{{$audit_trail->created_at->format("m/d/Y h:i A")}}</td>
        </tr>
        @empty
        <td colspan="6">
            <p class="text-center">No record found yet.</p>
        </td>
        @endforelse
    </tbody>
</table>
@stop