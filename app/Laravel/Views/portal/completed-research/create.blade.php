@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Completed Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Completed Research</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-10 col-lg-8">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Submit Research</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data" id="completed_research_form">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_title">Approved Research</label>
                        {!! html()->select('title', ['' => "Select Research"] + $research, old('title'), ['id' => "input_title"])->class('form-control select2') !!}
                        @if($errors->first('title'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('title')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_research_file">Attach Approved Research (pdf)</label>
                        <input type="file" id="input_research_file" class="form-control" name="research_file" value="{{old('research_file')}}">
                        @if($errors->first('research_file'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('research_file')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_abstract">Abstract</label>
                        <textarea class="summernote" id="input_abstract" name="abstract">{{old('abstract')}}</textarea>
                        @if($errors->first('abstract'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('abstract')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.completed_research.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info create-research">Submit</button>
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
            title: "Are you sure you want to submit this completed research?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if(result){
                $('#completed_research_form').submit();
            }
        });
    });
</script>
@stop