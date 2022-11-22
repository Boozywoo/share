
@section('title', trans('admin.auth.registration-title'))

<div class="middle-box text-center successscreen">
    <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="41.5" cy="39.5" r="38.5" fill="white"/>
        <path d="M38.828 54.828C38.048 55.608 37.024 56 36 56C34.976 56 33.952 55.608 33.172 54.828L17.172 38.828C15.608 37.264 15.608 34.736 17.172 33.172C18.736 31.608 21.264 31.608 22.828 33.172L36 46.344L69.4 12.944C62.092 5.004 51.644 0 40 0C17.908 0 0 17.908 0 40C0 62.092 17.908 80 40 80C62.092 80 80 62.092 80 40C80 32.46 77.876 25.432 74.248 19.408L38.828 54.828Z" fill="#3AC86A"/>
    </svg>
    <div class="successscreen__text text-black-50">
        {{trans('admin.auth.success-registration')}}
    </div>
    <a class="registrationscreen__registration-link js_form-ajax-back pjax-link" href="/admin/auth/login">
        {{trans('admin.auth.log_in')}}
    </a>
    <style>.modal,.modal-backdrop.in{display:none!important;}</style>
</div>
