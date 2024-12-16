@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Notifications</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Notifications</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>List of Notification</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="row">
                        <div class="col-12 mb-4">
                            Note: This items are notifications you will receive through and email.
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input input-check-all" id="check-all">
                                <label class="form-check-label" for="check-all">Check/Uncheck All</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_research" value="1" id="input_notitication_research" {{old('has_research', $notification ? $notification->has_research : 0) == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="input_notitication_research">Can receive notifications researchers research documents status.</label>
                                @if($errors->first('has_research'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('has_research')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_student_research" value="1" id="input_notitication_student_research" {{old('has_student_research', $notification ? $notification->has_student_research : 0) == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="input_notitication_student_research">Can receive notifications for evaluated research documents.</label>
                                @if($errors->first('has_student_research'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('has_student_research')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_all_research" value="1" id="input_notitication_all_research" {{old('has_all_research', $notification ? $notification->has_all_research : 0) == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="input_notitication_all_research">Can receive notifications for all submitted research documents.</label>
                                @if($errors->first('has_all_research'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('has_all_research')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_completed_research" value="1" id="input_notitication_completed_research" {{old('has_completed_research', $notification ? $notification->has_completed_research : 0) == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="input_notitication_completed_research">Can receive notifications for completed research documents.</label>
                                @if($errors->first('has_completed_research'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('has_completed_research')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_posted_research" value="1" id="input_notitication_posted_research" {{old('has_posted_research', $notification ? $notification->has_posted_research : 0) == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="input_notitication_posted_research">Can receive notifications for posted research documents.</label>
                                @if($errors->first('has_posted_research'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('has_posted_research')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="has_archives" value="1" id="input_notitication_archives" {{old('has_archives', $notification ? $notification->has_archives : 0) == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="input_notitication_archives">Can receive notifications for posted research documents.</label>
                                @if($errors->first('has_archives'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('has_archives')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mt-4 mb-2">
                            <a href="{{route('portal.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-info">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(function(){
        $('#check-all').on('change', function(){
            if($(this).is(':checked')){
                $('.form-check-input').prop('checked', true);
            }
            else{
                $('.form-check-input').prop('checked', false);
            }
        });
    });
</script>
@stop