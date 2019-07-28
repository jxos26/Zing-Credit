@if($type == 'USER CREATED')
	<p>Dear <b>{{$name}}</b>,</p>
	<br />
	<p>Greetings!</p>
	
	<p>{!! $remarks !!}</p>
	
	<p>Click here to <a href="https://reporting.optiauto.net/" target="_blank" >LOGIN</a>.</p>
	
	<p>Should you have any query, feel free to contact us at:</p> 
	<p style="margin-left:50px">Call Us : (410) 305-9910</p>
	<br />
	<h4>All the best,</h4>
	<h4>- OptiAuto</h4>
	<hr>
	<p style="font-size:10px;">This is an email service from  <a href="https://reporting.optiauto.net/" target="_blank" >OptiAuto Reporting</a> and you are receiving this e-mail as an OptiAuto Reportin valued client. No reply needed.</p>	
@endif