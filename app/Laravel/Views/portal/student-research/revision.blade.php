@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Student Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Student Research</a></div>
        <div class="breadcrumb-item active"><a href="#">Revision</a></div>
        <div class="breadcrumb-item">Details</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-9 col-lg-7">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Revise Research</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data" id="research_form">
                    {!!csrf_field()!!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_research_file">Attach Revised File</label>
                                <input type="file" id="input_research_file" class="form-control" name="research_file" value="{{$research->filename}}">
                                @if($errors->first('research_file'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('research_file')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_research_type">Research Type</label>
                                {!! html()->select('research_type', $research_types, $research->research_type_id, ['id' => "input_research_type"])->class('form-control selectric') !!}               
                                @if($errors->first('research_type'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('research_type')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_title">Title</label>
                        <input type="text" id="input_title" class="form-control" placeholder="Title" name="title" value="{{$research->title}}">
                        @if($errors->first('title'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('title')}}</small>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_chapter">Chapter</label>
                                <input type="number" id="input_chapter" class="form-control" placeholder="0" name="chapter" value="{{$research->chapter}}" min="0">
                                @if($errors->first('chapter'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('chapter')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_version">Version</label>
                                <input type="number" id="input_version" class="form-control" placeholder="0.0" name="version" value="{{$research->version}}" step="0.1" min="0">
                                @if($errors->first('version'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('version')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_share_file">Shared Access</label>
                        {!! html()->multiselect('share_file[]', $authors, $shared, ['id' => "input_share_file"])->class('form-control select2') !!}
                        @if($errors->first('share_file'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('share_file')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.student_research.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info create-research">Return Research</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(".create-research").on('click', function(e){
        e.preventDefault();

        swal({
            title: "Are you sure you want to revise this?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if(result){
                $('#research_form').submit();
            }
        });
    });
</script>
@stop