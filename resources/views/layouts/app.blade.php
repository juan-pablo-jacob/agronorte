<!DOCTYPE html> 
<html lang="en">
    <head> 

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title> Agronorte Admin </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">

        <!-- Favicons -->

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{url('assets/images/icons/apple-touch-icon-144-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{url('assets/images/icons/apple-touch-icon-114-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{url('assets/images/icons/apple-touch-icon-72-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" href="{{url('assets/images/icons/apple-touch-icon-57-precomposed.png')}}">
        <link rel="shortcut icon" href="{{url('assets/images/icons/favicon.png')}}">



        <!-- JS Core -->
        <script type="text/javascript" src="{{url('assets-minified/js-core.js')}}"></script>


        <!--Inclución de los archivos de CSS y js-->
        @include('admin.files.css_files')
        @include('admin.files.js_files')


        <script type="text/javascript">
$(window).load(function () {
    setTimeout(function () {
        $('#loading').fadeOut(400, "linear");
    }, 300);
});
        </script>
        <style>
            #loading {position: fixed;width: 100%;height: 100%;left: 0;top: 0;right: 0;bottom: 0;display: block;background: #fff;z-index: 10000;}
            #loading img {position: absolute;top: 50%;left: 50%;margin: -23px 0 0 -23px;}
        </style>


        <script>
            var BASE_URL = "{{url('')}}";
        </script>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head> 
    <body>
        <div id="loading"><img src="{{url('assets/images/spinner/loader-dark.gif')}}" alt="Loading..."></div>

        <div id="sb-site">
            <div id="page-wrapper">


                <!--HEADER-->
                <div id="page-header" class="clearfix">
                    <div id="header-logo" class="rm-transition">
                        <a href="#" class="tooltip-button hidden-desktop" title="Navigation Menu" id="responsive-open-menu">
                            <i class="glyph-icon icon-align-justify"></i>
                        </a>
                        <span>Agronorte Admin <i class="opacity-80">1.0</i>
                        </span>

                        <a id="collapse-sidebar" href="#" title="">
                            <i class="glyph-icon icon-chevron-left"></i>
                        </a>
                    </div>
                    <!-- #header-logo -->



                    <div id="header-right">
                        <div class="user-profile dropdown">
                            <a href="#" title="" class="user-ico clearfix" data-toggle="dropdown">
                                <img width="36" src="{{url('assets/dummy-images/no-image.png')}}" alt="">
                                <i class="glyph-icon icon-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu pad0B float-right">
                                <div class="box-sm">
                                    <div class="pad5A button-pane button-pane-alt text-center">
                                        <a href="{{ route('logout') }}" class="btn display-block font-normal btn-danger"
                                           onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                            <i class="glyph-icon icon-power-off"></i>
                                            Cerrar sesión.
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- #page-header -->


                <!--                Menu-->
                @include('menu/menu')

                <style>

                    #page-content {
                        background: #fff;
                    }
                </style>


                <script type="text/javascript">
                    /* Todo sortable */

                    $(function () {
                        $(".todo-sort").sortable({
                            handle: ".sort-handle"
                        });
                    });
                </script>

                <!-- #page-sidebar -->

                <div id="page-content-wrapper" class="rm-transition">

                    @yield('content')

                </div><!-- #page-content-wrapper -->
            </div><!-- #page-wrapper -->

        </div><!-- #sb-site -->


        <!--Inclución funciones globales de Javascript-->
        @include('admin.functions.functions_global_js')

    </body>
</html>