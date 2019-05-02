{{--Footer area starts--}}
<footer class="footer-area section-big">
    <div class="container">

        <div class="row">

            <div class="col-md-3 col-sm-6 ft-widget">
                <img src="{{ companyLogo() }}" alt="">
                <p>{{ setting('under_logo_description') }}</p>
            </div>

            <div class="col-md-3 col-sm-6 ft-widget">
                <h3>Contact Us</h3>
                <p><i class="fa fa-map-marker"></i> {{ companyAddress() }}</p>
                <p><i class="fa fa-phone"></i> <a href="tel:{{ setting('company_phone') }}">{{ setting('company_phone') }}</a></p>
                <p><i class="fa fa-paper-plane"></i> <a href="mailto:{{ setting('email_account_support') }}">{{ setting('email_account_support') }}</a></p>
            </div>

            <div class="col-md-3 col-sm-6 ft-widget">
                <h3>Quick Links</h3>
                <ul class="q-link">
                    @foreach($navigationMenu as $item)
                        @if($item->is_quick_link)
                            <li><a href="{{ $item->url }}">{{ $item->title }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="col-md-3 col-sm-6 ft-widget">
                <h3>Subscribe</h3>
                <p>Sign up today for hints, tips and the latest product news</p>
                <form id="mc-form" class="mc-form">
                    {{csrf_field()}}
                    <div class="newsletter-form">
                        <input type="email" name="subscribe_email" autocomplete="off" id="mc-email" placeholder="Your email" class="form-control">
                        <button class="btn mc-submit" type="submit">
                            <i class="fa fa-location-arrow"></i>
                        </button>
                        <div class="clearfix"></div>
                        <div class="mailchimp-alerts">
                            <div class="mailchimp-submitting"></div>
                            <div class="mailchimp-success"></div>
                            <div class="mailchimp-error"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</footer>
{{--Footer area ends--}}

<div class="copyright-text text-center">
    {!! companyCopyright() !!}
</div>