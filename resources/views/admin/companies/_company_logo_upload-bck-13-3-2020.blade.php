


{!! Form::open(['url' => url($form_url), 'class' => 'module_form company_logo', 'files' => true]) !!}

{!! Form::hidden('update_type', 'company_logo') !!}
@if (isset($admin_form) && $admin_form)
{!! Form::hidden('company_id', $company_item->id) !!}
@endif
<div class="form-group">
	<input type="file" name="company_logo" class="filestyle" data-input="false" accept="image/*" />
</div>
{!! Form::close() !!}


<?php /*
<div id="modal">
	<div id="main-cropper"></div>
	<a class="button actionUpload">
		<span>Upload</span>
		<input type="file" id="upload" value="Choose Image" accept="image/*">
	</a>
	<button id="showResult">show result</button>
</div>

<div id="result-wrapper">
	<img src="" id="result" style=" width: 200px; height: 100px;">
</div> 


<div id="container"></div>


<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
*/ ?>

<div id="container"></div>

<div class="clearfix"></div>
<button class="btn btn-success crop_me_btn">Crop Me</button>

@push('additional_scripts')



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.css">
<script src="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.js"></script>




<script>

	// https://github.com/shpontex/cropme


  var example = $('#container').cropme();
  example.cropme('bind', {
    url: '{{ asset("images/logo.png")}}'
  });
</script>

<?php /*
<style type="text/css">
	
	
	#modal {
		float:left;
		z-index:100;
		height:500px;
		width:400px;
		background: white;
		border: 1px solid #ccc;
		-moz-box-shadow: 0 0 3px #ccc;
		-webkit-box-shadow: 0 0 3px #ccc;
		box-shadow: 0 0 3px #ccc;
		text-align: center;
	}

	/*button, .button {
		position: relative;
		background-color: purple;
		color: white;
		padding: 10px 15px;
		border-radius: 3px;
		border: 1px solid #cccccc;
		font-size: 16px;
		font-weight: bold;
		display: block;
		cursor: pointer;
		margin: 0 auto;
		width: 200px;
		margin-bottom: 10px;
		text-transform: uppercase;
	}

	button.actionCancel, .button.actionCancel{
		background-color: #ddd;
		color: purple;
	}

	button.actionDone, .button.actionDone{
		display: none;
	}
	
	input[type="file"] {
		position: absolute;
		top: 0;
		left: 0;
		opacity: 0;
	} */

/*
	#main-cropper{
		display: none;
	}

	#result-wrapper{
		float:right;
		img{border: 1px solid}
	}

</style>


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.min.js"></script>

*/ ?>

<script type="text/javascript">
	var basic = $("#main-cropper").croppie({
            viewport: { width: 300, height: 300, type: 'square' },
            boundary: { width: 200, height: 100 },
            showZoomer: true,
            url: "http://lorempixel.com/500/400/",
            enableExif: true
	});

	function readFile(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$("#main-cropper").show().croppie("bind", {
					url: e.target.result
				});
				
			};
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".actionUpload input").on("change", function() {
		readFile(this);
	});
	$(".actionDone").on("click", function() {
		$(".actionDone").toggle();
		$(".actionUpload").toggle();
	});

	$("#showResult").click(function() {
		$("#main-cropper")
		.croppie("result", {
			type: "canvas",
			size: "viewport",
			circle: false
		})
		.then(function(resp) {
			$("#result").attr("src", resp);

			var data = new FormData();
			data.append('file', resp);
			data.append('_token', '{{ csrf_token() }}');

			$.ajax({
				url :  "{{ route('upload-company-logo') }}",
				type: 'POST',
				data: data,
				contentType: false,
				processData: false,
				success: function(data) {
					alert("boa!");
				},    
				error: function() {
					alert("not so boa!");
				}
			});

			alert ('tsetstsetsetset');

		});
	});

</script>



@endpush

