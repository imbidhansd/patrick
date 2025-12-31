@php
$aweberEnabledSwitchClass = '';
$display_aweber_configuration = 'display: none;';
$display_service_category_type = 'display: none;';
$display_top_level_category = 'display: none;';
$ariaPressed = 'false';
if (isset($formObj) && $formObj->aweber_enabled) {
    $aweberEnabledSwitchClass = ' active';
    $display_aweber_configuration = '';
    $ariaPressed = 'true';
}

if (isset($formObj) && isset($formObj->trade_id)) {
    $display_service_category_type = '';
    $display_top_level_category = '';
}
@endphp
<style>
    th.grid-title {
  font-size: 12px; /* adjust as needed */
  font-weight: bold;
  text-transform: uppercase;
  color: #333; /* adjust color as needed */
  padding: 10px; /* adjust padding as needed */
  border-bottom: 2px solid #333; /* add a border at the bottom of the header */
}
.warning-message {
    color: #f00; /* red text color */
  background-color: #ffe5e5; /* light red background color */
  border: 1px solid #f77; /* less sharp red border */
  padding: 10px; /* adjust padding as needed */
  display: block; /* display the message as a block element */
  white-space: normal; /* allow text to wrap within the cell */
  border-radius: 5px; /* add rounded corners */
  box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* add a box shadow */
}
</style>
<div class="toggle-switch">
    <div class="row pb-3">
        <div class="col-sm-5">          
            <label for="aweber_member_request_list" class="font-bold">Aweber List for Member Request</label>
            <a class="ml-1 font-20" href="javscript:void(0);">
                <i class="mdi mdi-help-circle" data-toggle="tooltip" data-placement="top" title="This Aweber list will be used to parse member specific requests"></i>
            </a>
            <select class="form-control custom-select" name="aweber_member_request_list" id="aweber_member_request_list">
                @foreach ($aweberLists as $key => $value)
                    <option value="{{ $key }}" @if (isset($formObj->aweber_member_list) && $formObj->aweber_member_list == $key) selected @endif>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">       
        <div class="col-md-12" id="section_aweber_configuration" style="{{ $display_aweber_configuration }}">
            <div class="card w-100">
                <div class="card-body">                                    
                    <div class="row aweber service_configuration">
                        <div class="table-responsive list-page">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="grid-title" style="font-weight:bold;">Service Categories</th>
                                        <th class="grid-title" style="font-weight:bold;">Aweber Member List 
                                            <a class="ml-1 font-20" href="javscript:void(0);">
                                                <i class="mdi mdi-help-circle" data-toggle="tooltip" data-placement="top" title="This Aweber list will be used to parse general requests when members are available"></i>
                                            </a>
                                        </th>
                                        <th class="grid-title" style="font-weight:bold;">Aweber No Member List
                                            <a class="ml-1 font-20" href="javscript:void(0);">
                                                <i class="mdi mdi-help-circle" data-toggle="tooltip" data-placement="top" title="This Aweber list will be used to parse general requests when members are not available"></i>
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody> 
                                @if(isset($formObj->main_category_list) && !$formObj->main_category_list->isEmpty())                             
                                    @foreach ($formObj->main_category_list as $item)
                                        <tr>
                                            <td>{{$item->main_category->title}}-{{$item->service_category_type->title}}</td>
                                            <td>
                                                <select class="form-control custom-select" name="aweber_member_list_{{$item->id}}" id="aweber_member_list{{$item->id}}">
                                                    @foreach ($aweberLists as $key => $value)
                                                        <option value="{{ $key }}" @if (isset($item) && isset($item->aweber_member_listname) && $item->aweber_member_listname == $key) selected @endif>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control custom-select" name="aweber_non_member_list_{{$item->id}}" id="aweber_non_member_list{{$item->id}}">
                                                    @foreach ($aweberLists as $key => $value)
                                                        <option value="{{ $key }}" @if (isset($item) && isset($item->aweber_non_member_listname) && $item->aweber_non_member_listname == $key) selected @endif>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                       <td colspan="3">
                                        <div class="warning-message">
                                            No Main Categories assigned to the Affiliate. First assign Main Categories(Affiliates > Edit) before mapping Aweber lists.
                                        </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />
<button type="submit" id="submitConfiguration" class="btn btn-info float-right waves-effect waves-light">Submit</button>
