<?php $__env->startSection('title', 'Edit Itinerary PDF - Departure Cloud'); ?>

<?php $__env->startSection('content'); ?>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
             <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Edit Itinerary</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Itinerary</li>
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
                        
                            <form role="form" id="AgentItineraryForm" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row d-flex align-items-center">
                                <div class="col-md-5"><br>
                                    <!-- <div class="from-group choosebannerselect mr-3"> -->
                                        <input type="hidden" name="itinerary_id" value="<?php echo e($pdf_itinerary->id); ?>">
                                        <input type="hidden" name="old_pdf" value="<?php echo e($pdf_itinerary->pdf_file); ?>" id="old_pdf">
                                        <label for="img_file">Choose itinerary PDF, JPG, PNG</label><span class="validationError" id="image_error"></span><br>
                                        <input type="file" class="banner-img" id="pdf_name" name="pdf_name" accept="application/pdf, image/jpeg,image/png, image/jpg">
                                        <p>
                                        <span class="validationError" id="size_error"></span></p>
                                        <input type="hidden" id="fileSize">
                                    <!-- </div> -->
                                    <h4 class="mt-2">or</h4>
                                    <div class="form-group">
                                        <label for="">Itinerary Link</label>
                                        <input type="url" class="form-control" name="title" id="title" value="<?php echo e($pdf_itinerary->title); ?>">
                                    </div>
                                    <span class="validationError" id="title_error"></span>
                                </div>
                            <?php if(isset($pdf_itinerary->pdf_file)): ?>
                                <div class="col-md-6 ml-auto border-left">
                                    <div class="itineraryPreview">
                                       <?php if($extension == 'pdf'): ?>
                                            <embed src="<?php echo e(asset('agentitinerary') . '/' . $pdf_itinerary->pdf_file); ?>" type="application/pdf" width="100%" height="400px"/>
                                        <?php elseif($extension == 'png' || $extension == 'PNG' || $extension == 'jpeg' || $extension == 'JPEG' || $extension == 'jpg' || $extension == 'JPG'): ?>
                                            <img src="<?php echo e(asset('agentitinerary') . '/' . $pdf_itinerary->pdf_file); ?>" width="100%" height="400px">
                                        <?php else: ?>
                                        <img src="<?php echo e(asset('ScreenShot') . '/' . $pdf_itinerary->description); ?>" width="100%" height="400px">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                                <div class="col-md-12">
                                    <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                    <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif" style="width: 3%; visibility: hidden;">
                                    <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                            </div>

                            </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Itinearay Modal-->
    <style type="text/css">
        .steps.clearfix {
            margin-top: 10px
        }

        span.step-icon {
            padding-top: 10px
        }

        .steps.clearfix > ul > li {
            display: inline-flex;
            margin-right: 20px
        }

        .box.box-primary {
            border-top-color: #3c8dbc;
            background: 0 0
        }

        .radio {
            display: inline
        }

        .radio > label {
            margin-right: 30px
        }

        .validationError {
            color: #ff0c0c;
        }

        .button-submit {
            margin-top: 20px;
            margin-bottom: 20px
        }

        .ck.ck-content.ck-editor__editable {
            height: 150px;
        }

        span.ck-file-dialog-button {
            display: none;
        }

        .steps.clearfix.text-center {
            margin-top: 20px;
            padding-bottom: 20px;
        }

        .sss {
            padding: 8px;
        }

        .pwa-editor-bar-panel {
            display: none !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerSection'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#pdf_name').on('change', function(evt) {
                var size = this.files[0].size;
                var a = Math.ceil((size/1024)/1024);
                $("#fileSize").val(a);
            });
        });
        $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var title = $('#title').val();
                var pdf_name = $('#pdf_name').val();
                var old_pdf = $('#old_pdf').val();
                if (title == "" && pdf_name == "" && old_pdf == "") {
                    $("span#title_error").html('Attachment or Itinerary Finder Link is required!');
                    $("input#title").focus();
                    return false;
                } else {
                    $("span#title_error").hide();
                }
                var file_size = $('#fileSize').val();
                if (file_size > 48) {
                    $("span#size_error").html('File size should be less then <b>48 MB </b>!');
                    $("input#pdf_name").focus();
                    return false;
                } else {
                    $("span#size_error").hide();
                }
                $('#gif').css('visibility', 'visible');
                $('#store_form').html('Please wait...')
                $('#store_form').prop('disabled', true);
                var formDatas = new FormData(document.getElementById('AgentItineraryForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "<?php echo e(route('pdf_itinerary_update', $pdf_itinerary->departure_id)); ?>",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        location.reload();
                        window.location = data.url;
                       
                    },
                    errors: function () {

                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#description').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['style', ['bold', 'italic', 'underline']],
                    ['color', ['color']],
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
                            if (bufferText) {
                                document.execCommand('insertHtml', false, div.html());
                            } else {
                                document.execCommand('insertText', false, bufferText1);
                            }
                        }, 10);
                    }
                },
                styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                height: 150,
                focus: true
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/departurecloud/resources/views/departure/itinerary_pdf_edit.blade.php ENDPATH**/ ?>