<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #555d69fa;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        border-radius: 10px;
        width: 15%;
        color: white;
    }

    /* The Close Button */
    .closepas {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .closepas:hover,
    .closepas:focus {
        color: #aaaaaa;
        text-decoration: none;
        cursor: pointer;
    }
     input[type="submit"].forgetButton {
        background:
                seagreen none repeat scroll 0 0 padding-box;
        border: none;
        border-radius: 10px;
        color: white;
        cursor: pointer;
        font-family: calibri,sans-serif;
        line-height: 25px;
        width: 100%;
        margin-top: 12px;
        float: left;

</style>



<script>
    // Get the modal
    var modalpas = document.getElementById('myModalPass');

    // Get the button that opens the modal
    var btnpas = document.getElementById("myBtnPass");

    // Get the <span> element that closes the modal
    var spanpas = document.getElementsByClassName("closepas")[0];

    // When the user clicks the button, open the modal
    btnpas.onclick = function () {
        modalpas.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    spanpas.onclick = function () {
        modalpas.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modalpas) {
            modalpas.style.display = "none";
        }
    }
</script>