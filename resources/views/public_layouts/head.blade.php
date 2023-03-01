<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Coderthemes" name="author"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<!-- meta_add -->
<link rel="shortcut icon" href="{{asset('favicon.png')}}">
@section('tagSection')

@show

<link href="{{asset('assets1/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/libs/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/libs/datatables/select.bootstrap4.css')}}" rel="stylesheet" type="text/css"/>

<link href="{{asset('assets1/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css"/>

<link href="{{asset('assets1/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css"/>

<link href="{{asset('assets1/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/css/app.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/libs/summernote.summernote-bs4.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/css/dc_style.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/css/dc_chat.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets1/css/dc_pullit.css')}}" rel="stylesheet" type="text/css"/>
@section('headCssSection')

@show
<script src="{{asset('assets1/js/vendor.min.js')}}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P0T5QRLC3W"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'G-P0T5QRLC3W');
</script>
<style type="text/css">
    body::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    body::-webkit-scrollbar {
        width: 8px;
        background-color: #F5F5F5;
    }

    body::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #093E8E;
    }
    
</style>
    