@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Departments</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Departments</a></div>
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
                <h4>Create Department</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_dept_code">Code</label>
                        <input type="text" id="input_dept_code" class="form-control" placeholder="Code" name="dept_code"  value="{{old('dept_code')}}">
                        @if($errors->first('dept_code'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('dept_code')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_dept_name">Name</label>
                        <input type="text" id="input_dept_name" class="form-control" placeholder="Name" name="dept_name"  value="{{old('dept_name')}}">
                        @if($errors->first('dept_name'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('dept_name')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.cms.departments.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop