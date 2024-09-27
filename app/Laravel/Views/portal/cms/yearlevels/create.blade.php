@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Yearlevels</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Yearlevels</a></div>
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
                <h4>Create Yearlevel</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_yearlevel_name">Name</label>
                        <input type="text" id="input_yearlevel_name" class="form-control" placeholder="Name" name="yearlevel_name"  value="{{old('yearlevel_name')}}">
                        @if($errors->first('yearlevel_name'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('yearlevel_name')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.cms.yearlevels.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop