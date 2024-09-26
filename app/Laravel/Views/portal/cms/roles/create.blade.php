@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Roles</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Roles</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<form method="POST" action="">
    {!!csrf_field()!!}
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4>Create Role</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="input_role">Role</label>
                        <input type="text" id="input_role" class="form-control" placeholder="Role" name="role"  value="{{old('role')}}">
                        @if($errors->first('role'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('role')}}</small>
                        @endif                  
                    </div>
                    <div class="form-group">
                        <label for="input_status">Status</label>
                        {!! html()->select('status', $statuses, old('status'), ['id' => "input_status"])->class('form-control selectric') !!}
                        @if($errors->first('status'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('status')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.cms.roles.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4>Role Permissions</h4>
                </div>
                <div class="card-body">
                    @if($errors->first('permissions'))
                    <small class="d-block mt-1 text-danger">{{$errors->first('permissions')}}</small>
                    @endif
                    <div id="accordion">
                        @foreach($permissions as $module_name => $perms)
                        <div class="accordion">
                            <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-{{str_replace(' ', '_', $module_name)}}" aria-expanded="false">
                                <h4>{{$module_name}}</h4>
                            </div>
                            <div class="accordion-body collapse" id="panel-body-{{str_replace(' ', '_', $module_name)}}" data-parent="#accordion">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input input-check-all" id="check-all-{{$module_name}}" data-module="{{$module_name}}">
                                    <label class="form-check-label" for="check-all-{{$module_name}}">Check/Uncheck All</label>
                                </div>
                                <div class="row">
                                    @foreach($perms as $permission)
                                    <div class="col-sm-12 col-md-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{$permission->name}}" id="permission-{{$permission->name}}" data-module="{{$module_name}}">
                                            <label class="form-check-label" for="permission-{{$permission->name}}">{{$permission->description}}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(function(){
        $(document).on('change', '.input-check-all', function() {
            var is_checked = $(this).is(':checked');
            var module_name = $(this).data('module');

            $('input.permission-checkbox[data-module="' + module_name + '"]').prop('checked', is_checked);
        });
    });
</script>
@stop