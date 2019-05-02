{{--CONTACT Us starts--}}
<div id="contact" class="contact-area-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2>Contact Us</h2>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="map-contact-area clearfix">
    <div class="map-area hidden-xs">
        <div id="contactgoogleMap"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <div class="contact-form clearfix">
                    <div id="form-messages"></div>
                    <form id="ajax-contact" action="{{url('contact-us')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group in_name">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" required="required">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group in_email">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" required="required">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group in_email">
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" required="required">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group in_message">
                                    <textarea name="message" class="form-control" id="message" rows="5" placeholder="Message" required="required"></textarea>
                                </div>
                                <div class="actions">
                                    <input type="submit" value="Send Message" name="submit" id="submitButton" class="btn" title="Submit Your Message!">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{--CONTACT Us ends--}}