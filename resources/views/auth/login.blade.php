@extends('layouts.app_login')

@section('content')
<div class="center-content">

    <div class="col-md-3 center-margin">
        <form role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="content-box wow bounceInDown modal-content">
                <div class="content-box-wrapper">
                    <div class="form-group"> 
                        <div class="input-group">
                            <input id="name" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Nombre de usuario">
                            <span class="input-group-addon bg-blue">
                                <i class="glyph-icon icon-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                            <span class="input-group-addon bg-blue">
                                <i class="glyph-icon icon-unlock-alt"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('password.request') }}" title="Recover password">Olvidé mi contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Entrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
