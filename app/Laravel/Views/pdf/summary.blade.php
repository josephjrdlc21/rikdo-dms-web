@extends('pdf._layouts.main')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" class="border-white">
    <tbody>
        <tr class="border-none">
            <td class="text-center border-none">
                <p class="lh1 fs14 text-center"><b>Summary</b></p>
                <p class="lh1 fs14 text-center"><b>{{Carbon::now()->format('F d, Y g:i A')}}</b></p>
            </td>
        </tr>
    </tbody>
</table>
<p>Researches Total: {{$research_total}}</p>
<table width="100%" cellpadding="1" cellspacing="0">
    <thead>
        <tr>
            <th class="text-center text-uppercase fs14">No.</th>
            <th class="text-center text-uppercase fs14">Status</th>
            <th class="text-center text-uppercase fs14">Quantity</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Pending</td>
            <td class="text-center">{{$research_total_pending}}</td>
        </tr>
        <tr>
            <td>2</td>
            <td>For Revision</td>
            <td class="text-center">{{$research_total_for_revision}}</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Approved</td>
            <td class="text-center">{{$research_total_approved}}</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Rejected</td>
            <td class="text-center">{{$research_total_rejected}}</td>
        </tr>
    </tbody>
</table>
<p>Complete Research Total: {{$completed_total}}</p>
<table width="100%" cellpadding="1" cellspacing="0">
    <thead>
        <tr>
            <th class="text-center text-uppercase fs14">No.</th>
            <th class="text-center text-uppercase fs14">Status</th>
            <th class="text-center text-uppercase fs14">Quantity</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Pending</td>
            <td class="text-center">{{$completed_total_pending}}</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Re Submission</td>
            <td class="text-center">{{$completed_total_re_submission}}</td>
        </tr>
        <tr>
            <td>3</td>
            <td>For Posting</td>
            <td class="text-center">{{$completed_total_for_posting}}</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Rejected</td>
            <td class="text-center">{{$completed_total_rejected}}</td>
        </tr>
    </tbody>
</table>
<p>Posted Research Total: {{$total_posted}}</p>
<table width="100%" cellpadding="1" cellspacing="0">
    <thead>
        <tr>
            <th class="text-center text-uppercase fs14">No.</th>
            <th class="text-center text-uppercase fs14">Name</th>
            <th class="text-center text-uppercase fs14">Quantity</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Posted</td>
            <td class="text-center">{{$total_posted}}</td>
        </tr>
    </tbody>
</table>
@stop