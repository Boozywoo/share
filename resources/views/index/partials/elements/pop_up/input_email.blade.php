<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>{{ trans('index.profile.please_enter_email_title') }}:</h3>
        <hr size="1">
        <div style="text-align: center">
            <input class="js_settings-input" name="email" type="text" value="" id="settings-input" placeholder="{{ trans('index.profile.please_enter_email_placeholder') }}">&nbsp;<input type="button" name="email" class="js_submit-email" value="{{ trans('index.profile.please_enter_email_submit') }}">
        </div>
    </div>

</div>
<style>
    /* The Modal (background) */
    .modal {
        display: block; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 30%; /* Could be more or less, depending on screen size */
    }

    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size:18px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .modal input {
        display: block;
        color:#404040;
        height: 37px;
        line-height: 37px;
        box-sizing: border-box;
        border: 1px solid #e8e8e8;
        width: 100%;
        padding-left: 15px;
    }
</style>