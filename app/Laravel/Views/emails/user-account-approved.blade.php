@extends('emails._layouts.main')

@section('content')
<table class="body-wrap">
	<tr>
		<td class="container" style="background-color: #FFFFFF">
			<div class="content">
                <table>
                    <tr>
                        <td>
                            <h2>RIKDO DMS</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Hello! {{$name}}</h4><br>
                            <p>Your account has been approved. Please use the following username or email and default password to log in:</p><br>
                            <table>
                                <tr>
                                    <td>
                                        <p><b>Username:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$username}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Email:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$email}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Default Password:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$password}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Date time:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$date_time}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <a href="{{route('portal.auth.login')}}" class="btn-primary mb-3">Login</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p class="mt-4">We recommend changing your password as soon as you log in for security reasons.</p>
                                    </td>
                                </tr>
                            </table>
                            <br><p>Regards,<br> Support Team</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="footnote">If you have any inquiries, please feel free to contact us through (+63) 47 361 2178 / (+63) 900 666
                            4456 or support@cc.rikdo.com</p>
                        </td>
                    </tr>
                </table>
			</div>				
		</td>
	</tr>
</table>
@stop