@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')


<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="#" title="Listado Usuarios"><span>Listado Usuarios</span></a></li>
        <li><a href="{{url("users/create")}}" title="Listado Usuarios"><span>Crear Usuario</span></a></li>
    </ul>
</div>

<div id="page-content">
    <!--    Inicio Filtro Búsqueda-->
    <div class="content-box">
        <h3 class="content-box-header bg-white">
            Filtro búsqueda de usuarios
        </h3>

        <div class="content-box-wrapper bg-white">
            <form action="{{url("/users")}}" method="get" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Nombre Usuario</label>
                    <input type="text" class="form-control col-md-2" id="name_busqueda" value="{{$request->name}}" name="name" placeholder="Nombre">
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="text" class="form-control" id="email_busqueda"  value="{{$request->email}}" name="email" placeholder="Email">
                </div>

                <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar</button>
                <a href="{{url("users")}}" class="btn btn-success"><i class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>
            </form>
        </div>
    </div>
    <!--    Fin Filtro Búsqueda-->


    <!--    Inicio Tabla-->

    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table-user">
        <thead>
            <tr>
                <th>Nombre Usuario</th>
                <th>Email</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @if(count($users)>0)
            @foreach ($users as $user)
            <tr class="odd gradeX">
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->nombre}}</td>
                <td>{{$user->apellido}}</td>
                <td class="center">
                    <a href="{{url("users/" . $user->id . "/edit")}}" tittle="Editar"><i class="glyph-icon icon-elusive-edit"></i></a>
                    <a href="javascript:;" data-id="{{$user->id}}" class="btn-borrar" tittle="Eliminar"><i class="glyph-icon icon-elusive-trash"></i></a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <!--    Fin Tabla-->

    <!--Modal eliminación registro-->
    <div class="modal fade bs-example-modal-sm" id="modal-delete-confirmation" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar registro</h4>
                </div>
                <div class="modal-body">
                    <p>Seguro que desea eliminar el usuario?</p>
                    <form id="user-delete-form" action="" style="display:none;" method="post">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-confirm-delete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    /* Datatables init */

    $(document).ready(function () {

        $(".btn-borrar").click(function () {
            var iduser = $(this).data("id");
            $('#user-delete-form').attr('action', BASE_URL + '/users/' + iduser);
            $('#modal-delete-confirmation').modal('toggle');
        });

        $(".btn-confirm-delete").click(function () {
            $('#user-delete-form').submit();
        });


        $('#dynamic-table-user').dataTable();
        $("#dynamic-table-user_length").hide();
        $("#dynamic-table-user_filter").hide();

        /* Add sorting icons */

        $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

    });

</script>

@endsection
