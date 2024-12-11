@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Pages</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Pages</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Create Page</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_type">Type</label>
                        {!! html()->select('type', $types, old('type'), ['id' => "input_type"])->class('form-control selectric') !!}
                        @if($errors->first('type'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('type')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_title">Title</label>
                        <input type="text" id="input_title" class="form-control" placeholder="Title" name="title"  value="{{old('title')}}">
                        @if($errors->first('title'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('title')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_content">Content</label>
                        <textarea class="summernote" id="input_content" name="content">{{old('content')}}</textarea>
                        @if($errors->first('content'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('content')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.cms.pages.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop