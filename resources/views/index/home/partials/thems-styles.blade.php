@if(!empty(\App\Models\SiteSetting::first()))
    @php($settings = \App\Models\SiteSetting::first())
    <style>
        .visModWrapp, .makeAReservation{
            font-size: {{$settings->font_size}}px !important;
            color: {{$settings->font_color}} !important;
        }
        form#reservations, .visModWrapp{
            background-color: {{$settings->background_color}} !important;
            opacity: {{$settings->opacity}} !important;
        }
        form#reservations, .visModWrapp, .makeAReservation, .additImageBlock > a {
            border-radius: {{$settings->border_radius}}px !important;
        }  
        .makeAReservation {
            background-color: {{$settings->button_color}} !important;
            border: none !important;
        }
        .reservationsWindWrapp_embedded .additImageBlock a{
            box-shadow: 0 0 0 2px {{$settings->button_color}} !important;
        }
        .text-dark {
            color: {{$settings->font_color_authorization_buttons}} !important;
        }
        .topBlock .registration > div > ul > li:first-of-type::after {
            background-color: {{$settings->font_color_authorization_buttons}} !important;
        }
        .backg {
            background-color: {{$settings->background_color}}fa !important;
            opacity: {{$settings->opacity}} !important;
        }
        .shedule {
            background-color: {{$settings->background_color}}fa !important;
            opacity: {{$settings->opacity}} !important;
        }
        
        
    </style>
@endif