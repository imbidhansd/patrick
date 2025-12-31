@if (isset($mail_variables) && count($mail_variables) > 0)
	<div class="clearfix">&nbsp;</div>
	<label>Mail Content Variables</label><br />
	@foreach ($mail_variables as $variable_item)
	<span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}"
	    data-clipboard-target="#var_{{ $loop->index }}"
	    class="badge badge-info badge-label variable">{{ $variable_item }}</span>
	@endforeach
@endif