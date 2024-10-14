@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>My Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">My Research</a></div>
        <div class="breadcrumb-item active"><a href="#">Create</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-9 col-lg-7">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Create Research</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    {!!csrf_field()!!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_research_file">Research File</label>
                                <input type="file" id="input_research_file" class="form-control" name="research_file" value="{{old('research_file')}}">
                                @if($errors->first('research_file'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('research_file')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_research_type">Research Type</label>
                                {!! html()->select('research_type', $research_types, old('research_type'), ['id' => "input_research_type"])->class('form-control selectric') !!}               
                                @if($errors->first('research_type'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('research_type')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_title">Title</label>
                        <input type="text" id="input_title" class="form-control" placeholder="Title" name="title" value="{{old('title')}}">
                        @if($errors->first('title'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('title')}}</small>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_chapter">Chapter</label>
                                <input type="number" id="input_chapter" class="form-control" placeholder="0" name="chapter" value="{{old('chapter')}}">
                                @if($errors->first('chapter'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('chapter')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_version">Version</label>
                                <input type="number" id="input_version" class="form-control" placeholder="0.0" name="version" value="{{old('version')}}">
                                @if($errors->first('version'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('version')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_submit_to">Submit to</label>
                        {!! html()->select('submit_to', ['' => "Select Professor"] + $researchers, old('submit_to'), ['id' => "input_submit_to"])->class('form-control selectric') !!}
                        @if($errors->first('submit_to'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('submit_to')}}</small>
                        @endif             
                    </div>
                    <div class="form-group">
                        <label for="input_share_file">Share File</label>
                        {!! html()->multiselect('share_file[]', $researchers, old('share_file'), ['id' => "input_share_file"])->class('form-control select2') !!}
                        @if($errors->first('share_file'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('share_file')}}</small>
                        @endif                 
                    </div>
                    <a href="{{route('portal.research.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop