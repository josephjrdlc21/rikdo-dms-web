@extends('pdf._layouts.main')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" class="border-white">
    <tbody>
        <tr class="border-none">
            <td class="text-center border-none">
                <p class="lh1 fs14 text-center"><b>Research Report</b></p>
                <p class="lh1 fs14 text-center"><b>{{Carbon::now()->format('F d, Y g:i A')}}</b></p>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellpadding="1" cellspacing="0">
    <thead>
        <tr>
            <th class="text-center text-uppercase fs14">Title</th>
            <th class="text-center text-uppercase fs14">Type</th>
            <th class="text-center text-uppercase fs14">Status</th>
            <th class="text-center text-uppercase fs14">Department</th>
            <th class="text-center text-uppercase fs14">Course</th>
            <th class="text-center text-uppercase fs14">Yearlevel</th>
            <th class="text-center text-uppercase fs14">Student</th>
            <th class="text-center text-uppercase fs14">Adviser</th>
            <th class="text-center text-uppercase fs14">Date Created</th>
        </tr>
    </thead>
    <tbody>
        @forelse($record as $index => $researches)
        <tr>
            <td>{{$researches->title}}</td>
            <td>{{$researches->research_type->type ?? ''}}</td>
            <td>{{Helper::capitalize_text(str_replace('_', ' ', $researches->status)) ?? ''}}</td>
            <td>{{$researches->department->dept_code ?? ''}}</td>
            <td>{{$researches->course->course_code ?? ''}}</td>
            <td>{{$researches->yearlevel->yearlevel_name ?? ''}}</td>
            <td>{{$researches->submitted_by->name ?? ''}}</td>
            <td>{{$researches->submitted_to->name ?? ''}}</td>
            <td>{{$researches->created_at->format("m/d/Y h:i A") ?? ''}}</td>
        </tr>
        @empty
        <td colspan="9">
            <p class="text-center">No record found yet.</p>
        </td>
        @endforelse
    </tbody>
</table>
@stop