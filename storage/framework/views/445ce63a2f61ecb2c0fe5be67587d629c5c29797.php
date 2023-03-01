
<?php $__env->startSection('tagSection'); ?>
<title>Profile | Departure Cloud</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style type="text/css">
        .badge-primary {
            margin: 5px;
        }

        .search-list {
            list-style-type: none;
            margin-left: -10;
            margin-top: 5px;
        }
        .socail, dd{
            font-size: 13px !important;
        }
    </style>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Company Profile</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(session('message')): ?>
                <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
                    <div id="success_msg" style="">
                        <?php echo e(session('message')); ?>

                    </div>

                </div>
        <?php endif; ?>
        <!-- <?php if(count($user_destination)<0): ?>  <?php else: ?> m-auto <?php endif; ?> -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <figure class="profileCover" style="position:relative;">
                        <img src="<?php if(isset(Auth::user()->banner_image)): ?> <?php echo e(asset('BannerImage/' . Auth::user()->banner_image)); ?> <?php else: ?> <?php echo e(asset('/assets1/images/cover.jpg')); ?> <?php endif; ?>" alt="profileCover" class="img-fixed">
                        <div class="coverBannerOverlay"></div>
                    </figure>
                    <div class="d-md-flex align-items-center justify-content-between profilePicSection">
                        <div class="d-flex align-items-center">
                            <figure class="mb-0">
                                <img src="<?php if(isset(Auth::user()->logo)): ?><?php echo e(asset('companyLogo/' . Auth::user()->logo)); ?> <?php else: ?> <?php echo e(asset('images/no-image.png')); ?> <?php endif; ?>" class="img-fixed" alt="profile-image">
                            </figure>
                            <div class="ml-2">
                                <h5 class="text-blue1 mb-1 mt-2"><?php echo e($user->name); ?> <?php echo e($user->last_name); ?></h5>
                                <h3 class="mb-2 mt-0"><?php echo e(Auth::user()->company_name); ?></h3>
                            </div>
                        </div>
                        <div class="">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('company_profile_edit', $permission)): ?>
                                <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-example-modal-sm ">
                                    <i class="fas fa-user-edit"></i> Edit Profile
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-12 ">
                    
                </div> -->
                <div class="col-lg-4 col-xl-4">
                    <div class="card-box position-relative">
                        <h3>Company Details</h3>

                        <dl>
                            <dt><i class="fas fa-user"></i>Contact Name</dt>
                            <dd><?php echo e($user->flname); ?></dd>
                            <dt><i class="fas fa-phone fa-rotate-90"></i> Mobile</dt>
                            <dd><?php echo e($user->mobile); ?></dd>
                            <dt><i class="fas fa-envelope"></i> Email</dt>
                            <dd><a href="mailto:<?php echo e($user->email); ?>"><?php echo e($user->email); ?></a></dd>
                            <div class="socail">
                                <?php if(isset($user->website)): ?>
                                    <dt><i class="fa fa-globe" aria-hidden="true"></i> Website</dt>
                                    <dd><?php echo e($user->website); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($user->facebook)): ?>
                                    <dt><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</dt>
                                    <dd><?php echo e($user->facebook); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($user->twitter)): ?>
                                    <dt><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</dt>
                                    <dd><?php echo e($user->twitter); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($user->instagram)): ?>
                                    <dt><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</dt>
                                    <dd><?php echo e($user->instagram); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($user->youtube)): ?>
                                    <dt><i class="fa fa-youtube-play" aria-hidden="true"></i> YouTube</dt>
                                    <dd><?php echo e($user->youtube); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($user->website)): ?>
                                    <dt><i class="fa fa-pinterest-p" aria-hidden="true"></i> Pinterest</dt>
                                    <dd><?php echo e($user->pinterest); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($user->about)): ?>
                                    <dt><i class="fa fa-info-circle" aria-hidden="true"></i> About</dt>
                                    <dd><?php echo e($user->about); ?></dd>
                                <?php endif; ?>
                            </div>
                            <dt><i class="fas fa-map-marker-alt"></i> Address</dt>
                            <dd>
                                <?php if(isset($user->address)): ?>
                                    <?php echo e(ucfirst($user->address)); ?><br>
                                <?php endif; ?>
                                <?php if(isset($user->city)): ?>
                                    <?php echo e(ucfirst($user->city)); ?>,
                                <?php endif; ?>
                                <?php if(isset($user->state)): ?>
                                    <?php echo e(ucfirst($user->state)); ?>,<br>
                                <?php endif; ?>
                                <?php if(isset($user->country)): ?>
                                    <?php echo e(ucfirst($user->country)); ?> -
                                <?php endif; ?>
                                <?php if(isset($user->pin)): ?>
                                    <?php echo e(ucfirst($user->pin)); ?>

                                <?php endif; ?>
                            </dd>
                        </dl>
                    
                    </div>
                </div>
                <?php if(count($user_destination)>0): ?>
                    <div class="col-lg-8 col-xl-8">
                        <div class="card-box">
                            <h4>Destinations of Service</h4>
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Destination</th>
                                        <th>Region</th>
                                        <th>Country</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $user_destination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($row->destination_name); ?></td>
                                            <td><?php echo e($row->region); ?></td>
                                            <td><?php echo e($row->country); ?></td>
                                            <td>
                                                <a href="<?php echo e(url('/destination-delete/'.$row->id)); ?>" class="btn btn-sm btn-link text-danger font-12" onclick="return confirm('Are you sure? you want to delete destination')" title="Delete">
                                                    <i class="fas fa-times-circle "></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<style type="text/css">
    span.select2-container.select2-container--default.select2-container--open{z-index: 99999999;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerSection'); ?>

    <script src="<?php echo e(asset('js/customJS/basic-details_edit.js')); ?>"></script>
    <div class="modal fade bd-example-modal-sm" id="hold" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?php echo e(route('update_prifile')); ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex">
                            <h5 class="modal-title mr-2 btn btn-primary" id="mySmallModalLabel" style="color:#fff; background-color: #996383; border: none;"><a href="javascript:void(0);" onclick="editProfile('profile')" style="color:#fff">Edit Profile</a></h5>
                            <h5 class="modal-title btn btn-secondary" style="color:#fff"><a href="javascript:void(0);" onclick="editProfile('smtp')" style="color:#fff;">SMTP Details</a></h5>
                        </div>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                            <span style="color:#fff" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="t_id" value="<?php echo e(($user->id)); ?>">
                        <div class="generalInfo" id="generalInfo">
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="companyname">Company Name</label>
                                        <input type="text" class="form-control" id="companyname" name="company_name" placeholder="" value="<?php echo e($user->company_name); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="companyname">Company Logo</label>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="banner-img" name="logo" id="imgInp">
                                        <input type="hidden" name="old_logo" value="<?php echo e(Auth::user()->logo); ?>">
                                        <div class="choosebannerShow">
                                            <img id="blah" onclick="triggerImage()" src="<?php if(isset(Auth::user()->logo)): ?><?php echo e(asset('companyLogo/' . Auth::user()->logo)); ?> <?php else: ?> <?php echo e(asset('images/no-image.png')); ?> <?php endif; ?>" class="" width="60" height="50">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contactname">Contact Name</label>
                                        <select class="form-control w-100" name="name" id="contactname">
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $use): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($use->id); ?>" <?php if($use->id == $user->contact_person_id): ?> selected <?php endif; ?>><?php echo e($use->flname); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contactname">Additional Contact Name</label>
                                        <select class="form-control w-100" name="additional_person_id" id="additionalcontactname">
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $use): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($use->id); ?>" <?php if($use->id == $user->additional_person_id): ?> selected <?php endif; ?>><?php echo e($use->flname); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="companyname">     Banner Image
                                         </label>
                                        <div class="d-flex align-items-center">
                                        <!-- <label class="btn btn-sm btn-secondary" >Banner Image</label> 
                                        <input accept="image/*" type='file' id="imgInp" style="display:none;">-->
                                         <input type="file" name="banner_image" id="banner_image1">
                                             <div class="choosebannerShow">
                                                <img id="blah1" onclick="triggerImage1()" src="<?php if(isset(Auth::user()->banner_image)): ?><?php echo e(asset('BannerImage/' . Auth::user()->banner_image)); ?> <?php else: ?> <?php echo e(asset('images/no-image.png')); ?> <?php endif; ?>" class="" width="60" height="50">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="text-align:right">
                                    <span id="size_error" class="text-danger" style=""></span>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="" value="<?php echo e(ucfirst($user->address)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mobile">Contact No.</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="" value="<?php echo e($user->mobile); ?>">
                                    </div>
                                </div>
                                <div class="col-md-1" style="margin-left: -20px;">
                                    <div class="form-group">
                                        <label for="mobile">Currency</label>
                                        <select class="form-control input-group-text" name="currency" id="" style="padding:0;width:56px;background-color: #e9ecef;" required="">
                                            <option value=""></option>
                                            <?php $__currentLoopData = $currency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->currency_code); ?>,<?php echo e($row->currency_symbol); ?>" <?php if(Auth::user()->currency_symbol==$row->currency_symbol): ?> selected <?php endif; ?>><?php echo e($row->currency_symbol); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="" value="<?php echo e(ucfirst($user->city)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state" placeholder="" value="<?php echo e(ucfirst($user->state)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" id="country" name="country" placeholder="" value="<?php echo e(ucfirst($user->country)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pincode">Pin Code</label>
                                        <input type="text" class="form-control" id="pincode" name="pin_code" placeholder="" value="<?php echo e(ucfirst($user->pin)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Website</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-globe"></i></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="profession" name="website" placeholder="ex. www.example.com" value="<?php echo e(ucfirst($user->website)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Facebook</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-facebook-f"></i></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="facebook" placeholder="" value="<?php echo e(ucfirst($user->facebook)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Twitter</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="twitter" value="<?php echo e(ucfirst($user->twitter)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Instagram</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="instagram" value="<?php echo e(ucfirst($user->instagram)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Linkedin</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="profession" name="linkedin" value="<?php echo e(ucfirst($user->linkedin)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Youtube</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-youtube-play" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" class="form-control"  name="youtube" value="<?php echo e(ucfirst($user->youtube)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Pinterest</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-pinterest-p" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="profession" name="pinterest" value="<?php echo e(ucfirst($user->pinterest)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="profession">About Company</label>
                                         <div class="input-group date">
                                            <textarea class="form-control" id="profession" name="about"><?php echo e(ucfirst($user->about)); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php if(auth::user()->main_user_type == 1): ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Destinations<span class="text-danger">*</span></label>
                                            <span class="validationError" id="destinations_error"></span>
                                            <input type="hidden" name="destinations" id="destinationName" class="form-control destinationName">
                                            <input type="text" id="destinations" class="form-control destinations" placeholder="Search destinations..">
                                            <div class="autocomplete-items" style="display: none">
                                            </div>
                                            <div id="dropdest"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="smtpInfo" id="smtpInfo" style="display:none;">
                            <div class="row" style="margin-right: -17px; margin-left: -17px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Username</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php if(isset($smtp_data->user_name)): ?><?php echo e(($smtp_data->user_name)); ?><?php endif; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Password</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="password" class="form-control" id="password" name="password" value="<?php if(isset($smtp_data->password)): ?><?php echo e($smtp_data->password); ?><?php endif; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Port</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="text" class="form-control" id="port" name="port" value="<?php if(isset($smtp_data->port)): ?><?php echo e(($smtp_data->port)); ?><?php endif; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Host</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="text" class="form-control" id="host" name="host" value="<?php if(isset($smtp_data->host)): ?><?php echo e(($smtp_data->host)); ?><?php endif; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">From Email</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="text" class="form-control" id="from_mail" name="from_mail" value="<?php if(isset($smtp_data->from_mail)): ?><?php echo e(($smtp_data->from_mail)); ?><?php endif; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">From Name</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="text" class="form-control" id="from_name" name="from_name" value="<?php if(isset($smtp_data->from_name)): ?><?php echo e(($smtp_data->from_name)); ?><?php endif; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="profession">Reply To</label>
                                         <div class="input-group date">
                                            <div class="input-group-prepend">
                                                <!-- <span class="input-group-text" id="basic-addon1">
                                                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                                </span> -->
                                            </div>
                                            <input type="text" class="form-control" id="reply_to" name="reply_to" value="<?php if(isset($smtp_data->reply_to)): ?><?php echo e(($smtp_data->reply_to)); ?><?php endif; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button id="onSubmit" type="submit" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // conatactname
    $( document ).ready(function() {
        $("#contactname").select2();
        $("#additionalcontactname").select2();
   
        //// $('#myModal').modal({
        //     backdrop: 'static',
        //     keyboard: false  // to prevent closing with Esc button (if you want this too..........)
        // });
    });
    </script>
    <script>

            // $('#onSubmit').submit(function(){
            //     username = $('#user_name').val();
            //     password = $('#password').val();
            //     port = $('#post').val();
            //     host = $('#host').val();
            //     mailFrom = $('#from_mail').val();
            //     mailName = $('#from_name').val();
            //     replyTo = $('#reply_to').val();
            //     pass = password.length != 8;
            //     portNo = port.length != 3;
            //     if(!username || !password || !port || !host || !mailFrom || !mailName || !replyTo)
            //     {
            //         alert('Please Fill All field');
            //         if(pass){
            //             alert('Password length should be 8 Digit');
            //         }
            //         if(portNo){
            //             alert('Port length only 3 Char');
            //         }
            //     }
            // });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function () {
            readURL(this);
        });
        $("#myElem").show().delay(5000).queue(function (n) {
            $(this).hide();
            n();
        });

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#banner_image1").change(function () {
            readURL1(this);
        });
    </script>
    <style>
        #myElem {
            display: flex;
            justify-content: center;
            height: 100%;
            position: absolute;
            z-index: 999;
            bottom: 10px;
            width: 100%;

        }

        #success_msg {
            background: #0a3f8e;
            position: absolute;
            background: #0a3f8e;
            position: absolute;
            bottom: 46px;
            padding: 9px 42px;
            font-size: 16px;
            border-radius: 5px;
            border: 5px solid white;
        }
        .img-fixed {
            object-fit: unset;
        }
        dd {
            font-size: 1rem;
        }
    </style>


    <script type="text/javascript">
        function initDestinationAll(Lat, Long, dest_name, country, regions, iso3s) {

            dest_selected.push(
                {
                    'name': dest_name,
                    'country': country,
                    'lat': Lat,
                    'long': Long,
                    'region': regions,
                    'iso3': iso3s,
                }
            );
            $('#destinationName').val(JSON.stringify(dest_selected));
            set_dest_html();
        }
        imgInp.onchange = evt => {
          const [file] = imgInp.files
          if (file) {
            blah.src = URL.createObjectURL(file)
          }
        }



var _URL = window.URL || window.webkitURL;
$("#banner_image1").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
        var width=this.width;
         var height=this.height;
          $("#width").html(width);
          $("#height").html(height);
         if(width < 1200 || height < 400)
         {
           //$('#blah1').val('');
            $("#banner_image1").val('');
            $('#blah1').attr('src',"<?php echo e(asset('images/no-image.png')); ?>");
           $('#size_error').html("Width and heigth should not be less than (1200 * 400)px");
         }
         else{
             $('#size_erro').html(" ");
         }                          
        };
        img.src = _URL.createObjectURL(file);
    }
});

    </script>
    <?php
    if(count($user_destination) > 0){
    //print_r($imagePath);
    foreach($user_destination as $destination){  ?>
    <script type="text/javascript">
        var dest_name = '<?php  echo $destination->destination_name; ?>';
        var country = '<?php  echo $destination->country; ?>';
        var Lat = '<?php  echo $destination->latitude; ?>';
        var Long = '<?php  echo $destination->longitude; ?>';
        var regions = '<?php  echo $destination->region; ?>';
        var iso3s = '<?php  echo $destination->country_iso_3; ?>';
        initDestinationAll(Lat, Long, dest_name, country, regions, iso3s)

    </script>
    <?php }} ?>
    <style>
        .modal-dialog {
            max-width: 700px;
            margin: 0 auto;
            height: 100vh;
        }

        .modal {
            left: unset;
            padding-right: 0 !important;
        }

        .modal.fade .modal-dialog {
            -webkit-transform: translate(25%, 0);
            transform: translate(25%, 0);
        }

        .modal.show .modal-dialog {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }

        .modal-content {
            background-color: #fdfdfd;
            border-radius: 0;
        }

        .modal-backdrop.show {
            opacity: .7;
        }

        .modal-header {
            /*height: 61px;
            align-items: center;
            background-color: #093E8E;
            margin-top: 70px;
            border-radius: 0;*/
        }

        .form-group, label {
        / / line-height: 0.4284;
        }
        .select2-container{
            width: 100%;
            display: block;
        }
        .profilePicSection{position: relative;
     top:auto; 
    width: 100%;
    margin: auto;
    margin-top: -40px;
    background:linear-gradient(0deg, rgb(255 255 255 / 77%),rgb(255 255 255 / 100%) 75%, transparent);
    padding: 10px 20px;
    border-radius: 10px;}
    .coverBannerOverlay{background:linear-gradient(0deg, rgb(255 255 255 / 55%) 10%,transparent);height:100px;position: absolute;bottom: 0;width:100%;left:0;}

    </style>
    <script>
        function editProfile(type){ 
            if(type == 'profile'){
                $('#generalInfo').css('display','block');
                $('#smtpInfo').css('display','none');
            } else {
                $('#smtpInfo').css('display','block');
                $('#generalInfo').css('display','none');
                console.log('smtp');
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/profile/index.blade.php ENDPATH**/ ?>