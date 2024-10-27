@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Posted Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Posted Research</a></div>
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
                <h4>Post Research</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="input_research">For Posting Research</label>
                        {!! html()->select('research', $for_posting, old('research'), ['id' => "input_research"])->class('form-control select2') !!}      
                    </div>
                    <a href="{{route('portal.posted_research.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop