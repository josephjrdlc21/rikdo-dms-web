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
                            <p>Your password has been reset successfully! You can now log in with your new credentials.</p><br>
                            <table>
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
                                        <p><b>Date time:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$date_time}}</p>
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