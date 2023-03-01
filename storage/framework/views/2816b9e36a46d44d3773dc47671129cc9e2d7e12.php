<?php $__env->startSection('tagSection'); ?>
<title>Edit Terms & Payment | Departure Cloud</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
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
                    <h4 class="page-title">Edit Terms</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                            <li class="breadcrumb-item active">Terms</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box formCard">
                    <h4 class="mt-0 depnameHeading"><?php echo e($departure->title); ?></h4>
                    <?php echo $__env->make('layouts/itinerary_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <form method="post" id="InclusionForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="">
                           <div class="row">
                                <div class="col-md-11 ml-2">
                                    <div class="form-group">
                                       <label for="email"><br> Terms & Condition:</label>
                                       <textarea class="form-control" name="termspayment" id="description"  placeholder="Write here.."><?php echo e($departure->termspayment); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right mt-2">
                                   <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save </button>
                                   <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif" style="width: 3%; visibility: hidden;">
                                   <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                  <?php if(isset($departure->termspayment)): ?>
                                  <?php if($departure->status == 0): ?>
                                  <a href="javascript:void(0);" class="disableDepartue btn btn-primary float-right status"  data-id="<?php echo e($departure->id); ?>" data-status="<?php echo e($departure->status); ?>" title="Publish Departure" <?php if($inclusion < 1): ?> onClick="alert('Please fill the inclusion section before publishing the departure')" <?php elseif($DeparturePrice < 1): ?> 
                                    onClick="alert('Please ensure that the minimum price before publishing the departure')" <?php elseif($payment_schedule < 1 ): ?> onClick="alert('Please fill the payment schedule section before publishing the departure')" 
                                    <?php elseif($cancelation_schedule < 1 ): ?> onClick="alert('Please fill the cancelation schedule section before publishing the departure')" <?php elseif($departure->termspayment == ''): ?> onClick="alert('Please fill the Terms section before publishing the departure')" 
                                     <?php elseif($departure->termspayment == ''): ?> onClick="alert('Please fill the Terms section before publishing the departure')" <?php else: ?> data-toggle="modal" data-target="#myModal" <?php endif; ?> >Publish</a>

                                    <!-- <?php if($departure->company_publish == 0): ?>
                                        <a href="javascript:void(0);" class="disableDepartue btn btn-primary float-right mr-1 status"  data-id="<?php echo e($departure->id); ?>" data-status="<?php echo e($departure->status); ?>" title="Publish for Own" <?php if($inclusion < 1): ?> onClick="alert('Please fill the inclusion section before publishing the departure')" <?php elseif($DeparturePrice < 1): ?> 
                                        onClick="alert('Please ensure that the minimum price before publishing the departure')" <?php elseif($payment_schedule < 1 ): ?> onClick="alert('Please fill the payment schedule section before publishing the departure')"
                                        <?php elseif($cancelation_schedule < 1 ): ?> onClick="alert('Please fill the cancelation schedule section before publishing the departure')" 
                                         <?php elseif($departure->termspayment == ''): ?> onClick="alert('Please fill the Terms section before publishing the departure')" <?php else: ?> id="status_active" <?php endif; ?>>Publish for own</a>
                                    <?php endif; ?> -->
                                  <?php endif; ?>
                                  <?php endif; ?>
                                </div>
                            </div> 
                      </form>
                      
                </div>
            </div>   
        </div>
  </div>   

 
  <!-- The Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
        <div class="modal-dialog">
          <div class="modal-content" style="min-height: 152px;">
            <!-- <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div> -->
            <!-- Modal body -->
            <form>
            <div class="modal-body">
                <div class="form-check">
                  <input type="radio" class="form-check-input" id="publish2" name="publish" value="2" checked>
                  <label class="form-check-label" for="materialChecked">Publish for all</label>
                </div>      
                <?php if($departure->company_publish == 0): ?>      
                <div class="form-check">
                  <input type="radio" class="form-check-input" id="publish1" name="publish" value="1">
                  <label class="form-check-label" for="materialUnchecked">Publish for own company</label>
                </div>
                <?php endif; ?>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer mt-4">
              <button type="submit" class="btn btn-primary btn-sm"  id="status_active2" >Submit</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerSection'); ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript">

  // $("#status_active").click(function () {
  //   if (confirm("Are You sure, Want to publish this departure?"))
  //   var id = $(this).data("id");
  //   var status = $(this).data("status");
  //   //var flag = (status == 0)?'Buyer':'Buyer & Supplier';
  //   var token ="<?php echo e(csrf_token()); ?>";
  //   if(id){
  //       //alert(id)
  //   $.ajax({
  //     url: '/departure-company-publish/' + id,
  //     type: 'POST',
  //     data: {
  //         "id": id,
  //         "_token": token,
  //     },
  //     success: function (data) {
  //       console.log(data);
  //       alert('Departure has been published successfully.');
  //       window.location.href = "<?php echo e(route('departure')); ?>";
  //     }
  //   });
  //   }
  // });

   $("#status_active2").click(function () {
    if (confirm("Are You sure, Want to publish this departure?"))
    var id = <?php echo e(request()->route('id')); ?>;
    var status = $(this).data("status");
    var publish = $('input[name="publish"]:checked').val();;
    //var publish1 = $('#publish1').val();
    //alert(publish2);
    //var flag = (status == 0)?'Buyer':'Buyer & Supplier';
    var token ="<?php echo e(csrf_token()); ?>";
        if(publish == 2){
           
            $.ajax({
              url: '/departure-disable/' + id,
              type: 'POST',
              data: {
                  "id": id,
                  "_token": token,
              },
              success: function (data) {
                console.log(data);
                alert('Departure has been published successfully. Details will be reviewed and approved by the admin soon!');
                window.location.href = "<?php echo e(route('departure')); ?>";
              }
            });
        }
        if(publish == 1){
            $.ajax({
              url: '/departure-company-publish/' + id,
              type: 'POST',
              data: {
                  "id": id,
                  "_token": token,
              },
              success: function (data) {
                console.log(data);
                alert('Departure has been published successfully.');
                window.location.href = "<?php echo e(route('departure')); ?>";
              }
            });
        }
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
            url: "<?php echo e(route('terms_store',request()->route('id'))); ?>",
            data: $('#InclusionForm').serialize(),
            success: function (data) {
                $('#gif').hide();
                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                if(data.msg)
                {
                    location.reload();
                }
                else
                {
                    window.location = data.url;
                }
           
                
            },
            errors: function () {
              $('#gif').hide();
              $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
            }

        });
    });
});
$('#description').summernote({
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
        height:250,
        //focus: true,
        placeholder: 'Description ........'
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/departurecloud/resources/views/terms/edit.blade.php ENDPATH**/ ?>