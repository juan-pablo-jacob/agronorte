
<!--Impresión de errores-> validate REQUEST-->
@if ($errors->any())
<script>
@foreach($errors->all() as $error)
     alertify.error("<i class='glyph-icon icon-elusive-error'></i>&nbsp;{{$error}}");
@endforeach
</script>
@endif


@if (!empty($msg_errors) && count($msg_errors)>0)
<script>
@foreach($msg_errors as $error)
     alertify.error("<i class='glyph-icon icon-elusive-error'></i>&nbsp;{{$error}}");
@endforeach
</script>
@endif

<!--Impresión de mensajes comunes-->
@if(Session::has('message'))
<script>
    alertify.success("{{Session::get('message')}}");
</script>
@endif