<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="price" name="price" content="{{ $price }}">
        <meta id="id" name="id" content="{{ $id }}">
        <meta id="public_id" name="public_id" content="{{ $public_id }}">
        
        <meta id="success_rnkb" name="success_rnkb" content="{{$url}}/order/pay/on_success_rnkb">
        <meta id="fail_rnkb" name="fail_rnkb" content="{{$url}}/order/pay/on_fail_rnkb">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>

    </head>
    <body>

    </body>
    
</html>

<script>
    $(document).ready(function() {
        const urlSuccess = new URL($('#success_rnkb').attr('content'));
        urlSuccess.searchParams.append("invoiceId", $('#id').attr('content'));

        const urlFail = new URL($('#fail_rnkb').attr('content'));
        urlFail.searchParams.append("invoiceId", $('#id').attr('content'));

        pay(parseFloat($('#price').attr('content')), $('#id').attr('content'), urlSuccess.href ?? '{{$url}}', urlFail.href ?? '{{$url}}');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    this.pay = function(amount, account, linkSuccess, linkFail) { 

        var widget = new cp.CloudPayments();
        widget.pay('charge', { 
            publicId: $('#public_id').attr('content'), 
            description: 'Оплата заказа ' + $('#id').attr('content'), 
            amount: amount, 
            currency: 'RUB',  
            invoiceId: account, 
            accountId: 'user@example.com',
            skin: "classic",
            data: {
                paccount: account 
            },
        },
        {
            onSuccess: linkSuccess,
            onFail: linkFail,
        }
    )};

</script>

