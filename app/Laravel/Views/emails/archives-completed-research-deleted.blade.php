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
                            <h4>Hello!</h4><br>
                            <p>This is to inform you that you're completed research document has been deleted permanently. Below are details of research.</p><br>
                            <table>
                                <tr>
                                    <td>
                                        <p><b>Title:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$title}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Authors:</b></p>
                                    </td>
                                    <td>
                                        @foreach($authors as $author)
                                        <p>{{$author->name}}</p> 
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Status:</b></p>
                                    </td>
                                    <td>
                                        <p>{{$status}}</p>
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