@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Student Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Student Research</a></div>
        <div class="breadcrumb-item active"><a href="#">Share</a></div>
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
                <h4>Research Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Title</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$research->title ?: 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Type</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$research->research_type->type ?: 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Chapter</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$research->chapter ?: 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Version</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$research->version ?: 'N/A'}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Share Research</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_share_file">Share Access With</label>
                        {!! html()->multiselect('share_file[]', $authors, $shared, ['id' => "input_share_file"])->class('form-control select2') !!}
                        @if($errors->first('share_file'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('share_file')}}</small>
                        @endif                 
                    </div>
                    <a href="{{route('portal.student_research.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Share</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop