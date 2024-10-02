<ul class="nav nav-pills mb-4" id="myTab3" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{session()->get('current_progress') == 1 ? "active" : ""}}" id="information-tab3" href="{{session()->get('max_progress') >= 1 && session()->get('max_progress') != 3 ? route('portal.users.step_back', ['1', $user->id]) : '#'}}">Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{session()->get('current_progress') == 2 ? "active" : ""}}" id="credentials-tab3" href="{{session()->get('max_progress') >= 2 && session()->get('max_progress') != 3 ? route('portal.users.step_back', ['2', $user->id]) : '#'}}">Credentials</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{session()->get('current_progress') == 3 ? "active" : ""}}" id="completed-tab3" href="{{session()->get('current_progress') == 3 && session()->get('max_progress') == 3 ? route('portal.users.step_back', ['3', $user->id]) : '#'}}">Completed</a>
    </li>
</ul>