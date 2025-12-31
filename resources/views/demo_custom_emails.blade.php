<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link href="{{ asset('themes/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
</head>
<body>
	<div class="container">
		<div class="table-responsive">
			<table class="table table-hover">
			    <thead>
			        <tr>
			        	<th>#</th>
			        	<th>Title</th>
			        	<th>Email Content</th>
			        </tr>
			    </thead>
			    <tbody>
			    	@foreach ($demo_custom_emails AS $email_item)
			    	<tr>
			    		<td>{!! $email_item->id !!}</td>
			    		<td>{{ $email_item->email_name }}</td>
			    		<td>
			    			{!! $email_item->content !!}
			    		</td>
			    	</tr>
			    	@endforeach
			    </tbody>
			</table>
		</div>
	</div>
	
</body>
</html>