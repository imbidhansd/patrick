<div class="clearfix"></div>
<footer>
<div class="footer_sec" style="background-color:#36404e">
        <div>
            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12 br_no_border">
                    @if (isset($web_settings['company_page_footer_1']) && !is_null($web_settings['company_page_footer_1']))
                    {!! $web_settings['company_page_footer_1'] !!}
                    @endif
                </div>                
                <div class="col-lg-3 col-md-12 col-sm-12 br_no_border">
                    @if (isset($web_settings['company_page_footer_3']) && !is_null($web_settings['company_page_footer_3']))
                    {!! $web_settings['company_page_footer_3'] !!}
                    @endif
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 br_no_border">
                    @if (isset($web_settings['company_page_footer_4']) && !is_null($web_settings['company_page_footer_4']))
                    {!! $web_settings['company_page_footer_4'] !!}
                    @endif
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 br_no_border">
                    <h3 style="font-size: 22px;color: #fff;font-weight: 700;">SOCIAL MEDIA</h3>
                    <ul class="social_link">
                        @if (isset($web_settings['facebook']) && !is_null($web_settings['facebook']))
                        <li><a href="{!! $web_settings['facebook'] !!}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        @endif

                        @if (isset($web_settings['instagram']) && !is_null($web_settings['instagram']))
                        <li><a href="{!! $web_settings['instagram'] !!}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        @endif

                        @if (isset($web_settings['twitter']) && !is_null($web_settings['twitter']))
                        <li><a href="{!! $web_settings['twitter'] !!}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        @endif

                        @if (isset($web_settings['youtube']) && !is_null($web_settings['youtube']))
                        <li><a href="{!! $web_settings['youtube'] !!}" target="_blank"><i class="fab fa-youtube"></i></a></li>
                        @endif

                        @if (isset($web_settings['linkedin']) && !is_null($web_settings['linkedin']))
                        <li><a href="{!! $web_settings['linkedin'] !!}" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            @if (isset($web_settings['company_page_copyrights']) && !is_null($web_settings['company_page_copyrights']))
            {!! str_replace('#YEAR', date('Y'), $web_settings['company_page_copyrights']) !!}
            @endif
        </div>
    </div>
</footer>
