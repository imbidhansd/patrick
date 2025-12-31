@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <p class="text-center">Add these products to your listing and increase sales!</p>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')

    @php
    $contentArr = (array) json_decode($shopping_cart_obj->content);

    if (isset($contentArr['suggested_products'])){
    $suggested_product_ids = array_keys ((array) $contentArr['suggested_products']);
    $suggested_products = (array) $contentArr['suggested_products'];
    }
    $total_selected_price = 0;
    @endphp

    {!! Form::open(['url' => url('update-cart'), 'class' => 'module_form ']) !!}

    {!! Form::hidden('upgrade_type', 'add_suggested_products') !!}
    <div class="card-box">
        @if (isset($products) && count($products) > 0)
        <div class="table-responsive">
            <table class="table" width="100%" border="0">
                <thead>
                    <tr class="xs-hidden">
                        <th colspan="2">Suggested Products To Add To Your Listing</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Total</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products AS $key => $product_item)
                    @php
                    $removeBtnStyle = "display:none;";
                    $addBtnStyle = "";
                    $product_include = "no";
                    $selected_price = $product_item->price;
                    @endphp

                    @if (isset($suggested_product_ids) && in_array($product_item->id, $suggested_product_ids))
                    @php
                    $removeBtnStyle = "";
                    $addBtnStyle = "display:none;";
                    $product_include = "yes";
                    $selected_price = $suggested_products[$product_item->id]->price;
                    $total_selected_price += $selected_price;
                    @endphp
                    @endif
                    <tr class="xs-hidden">
                        <td>{{ $product_item->title }}</td>
                        <td>
                            <a href="javascript:;" data-toggle="modal" data-target="#detailModel_{{ $product_item->id }}">Show more details <i class="fa fa-plus"></i></a>
                        </td>
                        <td class="text-right">$
                            @if ($selected_price != $product_item->price)
                            {{ number_format($selected_price, 2) }} <br />
                            <strike>{{ number_format($product_item->price, 2) }}</strike>
                            @else
                            {{ number_format($product_item->price, 2) }}
                            @endif
                        </td>
                        <td class="text-right">1</td>
                        <td class="text-right">$
                            @if (isset($suggested_product_ids) && in_array($product_item->id, $suggested_product_ids))
                            <span class="cart_added_price">
                                @if ($selected_price != $product_item->price)
                                {{ number_format($selected_price, 2) }} <br />
                                <strike>{{ number_format($product_item->price, 2) }}</strike>
                                @else
                                {{ number_format($selected_price, 2) }}
                                @endif
                            </span>
                            @else
                            <span class="cart_added_price">0.00</span>
                            @endif
                        </td>
                        <td class="text-center cart_btn">
                            <a href="javascript:;" title="Add to Cart" class="btn btn-orange btn-xs add_to_cart" data-price="{{ $selected_price }}" data-title="{{ $product_item->id }}" id="add_to_cart_btn_{{ $product_item->id }}" style="{{ $addBtnStyle }}"><i class="fas fa-cart-plus"></i> Add To Cart</a>
                            <a href="javascript:;" title="Remove From Cart" class="btn btn-danger btn-xs remove_from_cart" data-price="{{ $selected_price }}" style="{{ $removeBtnStyle }}"><i class="fas fa-cart-arrow-down"></i> Remove From Cart</a>
                            {!! Form::hidden('suggested_product_id[]', $product_item->id) !!}
                            {!! Form::hidden('suggested_product_title[]', $product_item->title) !!}
                            {!! Form::hidden('suggested_product_price[]', $selected_price) !!}
                            {!! Form::hidden('suggested_product_include[]', $product_include, ['class' => 'suggested_product_include']) !!}
                        </td>


                        <!-- Modal Start -->
                        <div class="modal fade message_detail_model" id="detailModel_{{ $product_item->id }}"
                            tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">
                                            {{ $product_item->title }}</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! $product_item->content !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <h5>
                                            Price: $
                                            @if ($selected_price != $product_item->price)
                                            {{ number_format($selected_price, 2) }} <br />
                                            <strike>{{ number_format($product_item->price, 2) }}</strike>
                                            @else
                                            {{ number_format($selected_price, 2) }}
                                            @endif
                                        </h5>
                                        <button data-product_id="{{ $product_item->id }}"
                                            class="btn btn-orange modal_add_to_cart_btn" type="button"
                                            data-dismiss="modal"><i class="fas fa-cart-plus"></i> Add To Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal End -->
                    </tr>
                    
                    <tr class="xs-visible">
                        <td>
                            <b>Suggested Products To Add To Your Listing: </b> {{ $product_item->title }}

                            <br />

                            <a href="javascript:;" data-toggle="modal" data-target="#detailModel_{{ $product_item->id }}">Show more details <i class="fa fa-plus"></i></a>

                            <br />

                            <b>Price: </b> 
                            @if ($selected_price != $product_item->price)
                            ${{ number_format($selected_price, 2) }} <br />
                            <strike>${{ number_format($product_item->price, 2) }}</strike>
                            @else
                            ${{ number_format($product_item->price, 2) }}
                            @endif

                            <br />

                            <b>Quantity: </b> 1

                            <br />

                            <b>Total: </b> 
                            @if (isset($suggested_product_ids) && in_array($product_item->id, $suggested_product_ids))
                            <span class="cart_added_price">
                                @if ($selected_price != $product_item->price)
                                ${{ number_format($selected_price, 2) }} <br />
                                <strike>${{ number_format($product_item->price, 2) }}</strike>
                                @else
                                ${{ number_format($selected_price, 2) }}
                                @endif
                            </span>
                            @else
                            $<span class="cart_added_price">0.00</span>
                            @endif

                            <br />

                            <a href="javascript:;" title="Add to Cart" class="btn btn-orange btn-xs add_to_cart" data-price="{{ $selected_price }}" data-title="{{ $product_item->id }}" id="add_to_cart_btn_{{ $product_item->id }}" style="{{ $addBtnStyle }}"><i class="fas fa-cart-plus"></i> Add To Cart</a>
                            <a href="javascript:;" title="Remove From Cart" class="btn btn-danger btn-xs remove_from_cart" data-price="{{ $selected_price }}" style="{{ $removeBtnStyle }}"><i class="fas fa-cart-arrow-down"></i> Remove From Cart</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot class="xs-hidden">
                    <tr style="{{ (($total_selected_price == 0) ? 'display: none;' : '') }}">
                        <td colspan="6" class="text-right">
                            <button type="button" class="btn btn-primary btn-sm total_cart_btn">
                                Total: $ <span
                                    class="total_cart_charges">{{ number_format($total_selected_price, 2) }}</span>
                            </button>
                        </td>
                    </tr>
                </tfoot>
                <tfoot class="xs-visible">
                    <tr style="{{ (($total_selected_price == 0) ? 'display: none;' : '') }}">
                        <td>
                            <button type="button" class="btn btn-primary btn-sm total_cart_btn">
                                Total: $ <span class="total_cart_charges">{{ number_format($total_selected_price, 2) }}</span>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <div class="text-right">
            <button type="submit"
                class="btn btn-primary btn-sm submit_btn">{{ (($total_selected_price == 0) ? 'No Thanks, Continue To Checkout' : 'Continue To Checkout') }}</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@section ('page_js')
<script type="text/javascript">
    $(function (){

        $(document).on('click', '.modal_add_to_cart_btn', function(){
            $('#add_to_cart_btn_' + $(this).data('product_id')).trigger('click');
        });

        $(".add_to_cart").on("click", function (){
            var cart_price = parseFloat($(this).data("price"));

            $(this).parents("tr").find(".cart_added_price").text(cart_price.toFixed(2));

            $(this).hide();
            $(this).parent().find(".remove_from_cart").show();
            $(this).parent().find(".suggested_product_include").val("yes");
            cart_btn();
        });

        $(".remove_from_cart").on("click", function (){
           $(this).parents("tr").find(".cart_added_price").text("0.00");

            $(this).hide();
            $(this).parent().find(".add_to_cart").show();
            $(this).parent().find(".suggested_product_include").val("no");

            cart_btn();
        });
    });

    function cart_btn (){
        var total_price = 0;
        $(".cart_added_price").each (function (){
            total_price += parseFloat($(this).text());
        });

        if (total_price > 0){
            $(".total_cart_btn").parents("tr").show();
            $(".total_cart_btn .total_cart_charges").text(parseFloat(total_price).toFixed(2));

            $(".submit_btn").text("Continue To Checkout");
        } else {
            $(".total_cart_btn").parents("tr").hide();
            $(".total_cart_btn .total_cart_charges").text("0.00");

            $(".submit_btn").text("No Thanks, Continue To Checkout");
        }
    }
</script>
@endsection
