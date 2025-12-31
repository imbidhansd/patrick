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
			        	<th>Email Content</th>
			        	<th>Email type</th>
			        </tr>
			    </thead>
			    <tbody>
			    	@foreach ($followup_variables AS $email_item)
			    	<tr>
			    		<td>{!! $email_item->id !!}</td>
			    		<td>
			    			{!! $email_item->content !!}
			    		</td>
			    		<td>{{ $email_item->member_type }}</td>
			    	</tr>
			    	@endforeach
			    </tbody>
			</table>
		</div>
	</div>
	
</body>
</html>