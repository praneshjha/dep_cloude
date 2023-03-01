<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            @include('public_layouts.head')

 
    </head>
    <body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
        
            @include('public_layouts.header')
              
            <div class="main-container" id="container">
                <div id="content" class="main-content">
                    @section('content')
                    @show

                </div>
                <!--  END CONTENT AREA  -->
            </div>
        
 
    @include('public_layouts.footer')
    </body>

    </html>
