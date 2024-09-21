@if(session()->has('notification-status'))
<div class="alert alert-{{in_array(session()->get('notification-status'),['failed','error','danger']) ? 'danger' : session()->get('notification-status')}} alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>×</span>
        </button>
        <b>{{ucfirst(session()->get('notification-status'))}}!</b> {{session()->get('notification-msg')}}
    </div>
</div>
@endif