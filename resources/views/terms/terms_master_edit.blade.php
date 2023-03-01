@extends('layouts.app')
@section('tagSection')
<title>Edit Terms Master | Departure Cloud</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@section('content')
<style>
    .ui-datepicker{
        z-index: 9999999 !important;;
    }
</style>
<div class="wrapper">
    <div class="wrapperOverlay"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Edit Terms Master</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                            <li class="breadcrumb-item active">Terms Master</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                      <form method="post" id="TermsMaster">
                        @csrf
                        <input type="hidden" name="id" value="">
                           <div class="row">
                                <div class="col-md-11 ml-2">
                                    <div class="form-group">
                                      <input type="hidden" name="id" value="{{$term_master->id}}">
                                       <label for="email"><br>Terms & Conditions Master:</label>
                                        <textarea class="form-control" name="terms_master" id="terms_master">{!! $term_master->conditions !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right mt-2">
                                   <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Update </button>
                                   <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                   <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                            </div> 
                      </form>
                </div>
            </div>   
        </div>
  </div>  

<style type="text/css">
  .note-btn{
    color: black;
  }
  .note-btn:title{
    background-color: red;
  }
</style>            
@endsection
@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript">
$('#terms_master').summernote({
     toolbar: [
        ['style', ['style']],
        ['style', ['bold', 'italic', 'underline']],
        //['fontname', ['fontname']],
        //['fontsize', ['fontsize']],
        //['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['view', ['codeview']]
    ],
     callbacks: {
        onPaste: function (e) {
          var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
          var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
          e.preventDefault();
          var div = $('<div />');
          div.append(bufferText);
          div.find('*').removeAttr('style');
          setTimeout(function () {
          if(bufferText){
            document.execCommand('insertHtml', false, div.html());
          }else{
            document.execCommand('insertText', false, bufferText1);
          }
          }, 10);
        }
      },
    styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    height:350,
});

 $(document).ready(function () {
    $('#store_form').click(function (e) {

        e.preventDefault();
        $('#gif').show();
        var checkbox = $("input[type='checkbox']").val();
        //alert(checkbox);
        $('#gif').css('visibility', 'visible');
        $('#store_form').html('Please wait...')
           $('#store_form').prop('disabled', true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "{{ route('term_master_update') }}",
            data: $('#TermsMaster').serialize(),
            success: function (data) {
                $('#gif').hide();
                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                if(data.msg)
                {
                    location.reload();
                }
                else
                {
                    window.location.reload();
                }
           
            },
            errors: function () {
              $('#gif').hide();
              $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
            }

        });
    });
});

</script>
@endsection