@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Research Types</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Research Types</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Create Research Type</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_type">Type</label>
                        <input type="text" id="input_type" class="form-control" placeholder="Type" name="type"  value="{{old('type')}}">
                        @if($errors->first('type'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('type')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_max_chapter">Max Chapter</label>
                        <input type="number" id="input_max_chapter" class="form-control" placeholder="0" name="max_chapter"  value="{{old('max_chapter')}}" min="0" step="1">
                        @if($errors->first('max_chapter'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('max_chapter')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.cms.research_types.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop