@extends('layouts.app')

@section('content')
<div class="wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
          <h4 class="page-title">Chat Board</h4>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="javascript: void(0);">Chat</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('messages.shared.users')
            </div>
            <div class="col-md-9" id="app">
               
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('footerSection')
<!-- <script src="https://media.twiliocdn.com/sdk/js/chat/v3.3/twilio-chat.min.js"></script> -->
@endsection