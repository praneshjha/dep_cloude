@if(Auth::user()->email_verified_at == true && Auth::user()->verified == 0)
    @include('layouts.approval')
@else
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('layouts.head')

 
    </head>
    <body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N2RCC4T"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
            @include('layouts.header')

            @if(Request::route()->getName()!='chat_room' && Request::route()->getName()!='chat_room_details' )
            <div id="app">
                <div id="chat_compon" style="display: none;">
                     <dep-chat-component  :auth-user="{{ auth()->user() }}"></dep-chat-component>
                </div>
            </div>
             @endif
              
            <div class="main-container" id="container">
                <div id="content" class="main-content">
                    @section('content')
                    @show

                </div>
                <!--  END CONTENT AREA  -->
            </div>
        
 
    @include('layouts.footer')
    </body>

    </html>
@endif