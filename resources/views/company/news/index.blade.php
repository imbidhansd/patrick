@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')


<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        @if (isset($news) && count($news) > 0)

            @foreach ($news AS $news_item)
            <div class="card news_section">
                <div class="card-header bg-secondary text-white">
                    <div class="card-widgets">
                        <i class="far fa-calendar-alt"></i> {{ $news_item->date }}
                    </div>

                    <h3 class="card-title text-white mb-0">
                        <i class="far fa-newspaper"></i> {{ $news_item->title }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <div class="short_content">
                            <p>
                                {!! substr($news_item->short_content, 0, 200) !!}
                                <a href="javascript:;" class="read_full_content">Read More</a>
                            </p>
                        </div>

                        <div class="full_content" style="display: none;">
                            {!! $news_item->content!!}
                            <a href="javascript:;" class="read_less_content">Read Less</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="float-left">
                {!! $news->render() !!}
            </div>
        @endif
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')

<script type="text/javascript">
    $(function (){
        $(".read_full_content").on("click", function (){
            $(this).parents(".text-left").find(".short_content").hide();
            $(this).parents(".text-left").find(".full_content").show();
        });

        $(".read_less_content").on("click", function (){
            $(this).parents(".text-left").find(".short_content").show();
            $(this).parents(".text-left").find(".full_content").hide();
        });

        $("#close_btn").on("click", function (){
            $(this).parents("#welcome_block").remove();
        });
    });
</script>
@endsection
