<div id="page-sidebar" class="rm-transition">
    <div id="page-sidebar-wrapper">
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="#" title="Configuración sistema">
                        <i class="glyph-icon icon-linecons-cloud"></i>
                        <span>Configuración</span>
                    </a>
                    <ul>
                        <li class="header"><span>Sistema</span></li>
                        <li><a href="{{ url("/marca") }}"
                               title="Sistema"><span>Marcas Productos</span></a></li>
                        </li>
                        <li><a href="{{ url("/tipo_producto") }}"
                               title="Sistema"><span>Tipos Productos</span></a></li>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" title="Usuarios">
                        <i class="glyph-icon icon-users"></i><span>Usuarios</span>
                    </a>
                    <ul>
                        <li><a href="{{ url("/users") }}" title="Usuario"><span>Gestión Usuarios</span></a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" title="Clientes">
                        <i class="glyph-icon icon-linecons-user"></i>
                        <span>Clientes</span>
                    </a>
                    <ul>
                        <li><a href="{{ url("/cliente") }}"
                               title="Listado Clientes"><span>Listado Clientes</span></a></li>
                        <li><a href="{{ url("/cliente/create") }}" title="Crear Cliente"><span>Crear Cliente</span></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" title="Productos">
                        <i class="glyph-icon icon-gears"></i>
                        <span>Productos</span>
                    </a>
                    <ul>
                        <li><a href="{{ url("/producto") }}"
                               title="Listado Productos"><span>Listado Productos</span></a></li>
                        <li><a href="{{ url("/producto/create") }}" title="Crear Producto"><span>Crear Producto</span></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" title="Incentivos">
                        <i class="glyph-icon icon-elusive-pencil"></i>
                        <span>Incentivos</span>
                    </a>
                    <ul>
                        <li><a href="{{ url("/incentivo") }}"
                               title="Listado Incentivos"><span>Listado Incentivos</span></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" title="Propuestas">
                        <i class="glyph-icon icon-money"></i>
                        <span>Propuestas</span>
                    </a>
                    <ul>
                        <li><a href="{{ url("/propuesta/create") }}"
                               title="Crear Propuesta"><span>Crear Propuesta</span></a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div><!-- #sidebar-menu -->
    </div><!-- #page-sidebar-wrapper -->
</div><!-- #page-sidebar -->
