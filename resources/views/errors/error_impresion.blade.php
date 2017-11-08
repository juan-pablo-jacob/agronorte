<!DOCTYPE html> 
<html lang="en">
    <head> 

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title> Error Impresión </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Favicons -->

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{url('assets/images/icons/apple-touch-icon-144-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{url('assets/images/icons/apple-touch-icon-114-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{url('assets/images/icons/apple-touch-icon-72-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" href="{{url('assets/images/icons/apple-touch-icon-57-precomposed.png')}}">
        <link rel="shortcut icon" href="{{url('assets/images/icons/favicon.png')}}">



        <!-- JS Core -->

        <script type="text/javascript" src="{{url('assets-minified/js-core.js')}}"></script>



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




        <!-- HELPERS -->

        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/helpers/helpers-all.css')}}">

        <!-- ELEMENTS -->

        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/elements/elements-all.css')}}">

        <!-- Icons -->

        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/icons/fontawesome/fontawesome.css')}}">
        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/icons/linecons/linecons.css')}}">

        <!-- SNIPPETS -->

        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/snippets/snippets-all.css')}}">

        <!-- APPLICATIONS -->

        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/applications/mailbox.css')}}">




        <!-- Admin Theme -->

        <link rel="stylesheet" type="text/css" href="{{url('assets/themes/supina/layout.css')}}">
        <link id="layout-color" rel="stylesheet" type="text/css" href="{{url('assets/themes/supina/default/layout-color.css')}}">
        <link id="framework-color" rel="stylesheet" type="text/css" href="{{url('assets/themes/supina/default/framework-color.css')}}">
        <link rel="stylesheet" type="text/css" href="{{url('assets/themes/supina/border-radius.css')}}">

        <!-- Color Helpers CSS -->

        <link rel="stylesheet" type="text/css" href="{{url('assets/helpers/colors.css')}}">

    </head> 
    <body>
        <div id="loading"><img src="{{url('assets/images/spinner/loader-dark.gif')}}" alt="Loading..."></div>


        <style type="text/css">

            body {
                overflow: hidden;
                background: #fff;
            }

        </style>
        <script type="text/javascript" src="{{url('assets/widgets/wow/wow.js')}}"></script>
        
        <script type="text/javascript">
                /* WOW animations */

                wow = new WOW({
                    animateClass: 'animated',
                    offset: 100
                });
                wow.init();
        </script>

        <img src="{{url('assets/dummy-images/bg31.jpg')}}" class="login-img wow fadeIn" alt="">

        <div class="center-vertical">
            <div class="center-content">

                <div class="col-md-6 center-margin">
                    <div class="server-message wow bounceInDown inverse">
                        <h1>Error Impresión</h1>
                        <h2>{{$msg}}</h2>    
                    </div>
                </div>

            </div>
        </div>

        <!-- WIDGETS -->
        <link rel="stylesheet" type="text/css" href="{{url('assets-minified/demo-widgets.css')}}">
        <script type="text/javascript" src="{{url('assets-minified/demo-widgets.js')}}"></script>
    </body>
</html>
