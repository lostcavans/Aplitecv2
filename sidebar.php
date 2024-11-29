<?php
// sidebar.php


// Verificar si el usuario está logueado
if (!isset($_SESSION['id_user']) || !isset($_SESSION['id_cargo'])) {
    die("Acceso no autorizado.");
}

// Obtener el ID del cargo del usuario logueado
$id_cargo_usuario = $_SESSION['id_cargo'];
?>

<!-- SideBar -->
<section class="full-box cover dashboard-sideBar">
    <div class="full-box dashboard-sideBar-bg btn-menu-dashboard"></div>
    <div class="full-box dashboard-sideBar-ct">
        <!--SideBar Title -->
        <div class="full-box text-uppercase text-center text-titles dashboard-sideBar-title">
            Bien-Venido <i class="zmdi zmdi-close btn-menu-dashboard visible-xs"></i>
        </div>
        <!-- SideBar User info -->
        <div class="full-box dashboard-sideBar-UserInfo">
            <figure class="full-box">
                <img src="assets/img/20240916_173639-La1Jdudcy-transformed-removebg-preview.png" alt="UserIcon">
                <figcaption class="text-center text-titles">Aplicaciones Tecnologicas S.R.L</figcaption>
            </figure>
            <ul class="full-box list-unstyled text-center">
                <li>
                    <a href="#!">
                        <i class="zmdi zmdi-settings"></i>
                    </a>
                </li>
                <li>
					<a href="#!" class="btn-exit-system" onclick="confirmLogout()">
						<i class="zmdi zmdi-power"></i>
					</a>
				</li>
            </ul>
        </div>
        <!-- SideBar Menu -->
        <ul class="list-unstyled full-box dashboard-sideBar-Menu">


            <?php if ($id_cargo_usuario == 1): // Cargo 1: Acceso total ?>
                <li>
                    <a href="home.php">
                        <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Notificaciones
                    </a>
                </li>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Usuarios <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="register_user.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Registrar</a>
                        </li>
                        <li>
                            <a href="list_users.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Modificar/Eliminar</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="map.php">
                        <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Mapa
                    </a>
                </li>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Informes Auditoria <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="report_user.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Usuarios</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Informes Usuarios <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="list_users_info.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Informaciones</a>
                        </li>
                        <li>
                            <a href="map_count.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Zonas</a>
                        </li>
                        <li>
                            <a href="graf_inst.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Empresas</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Estaciones <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="register_station.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Registrar</a>
                        </li>
                        <li>
                            <a href="list_station.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Modificar/Eliminar</a>
                        </li>
                    </ul>
                </li>
             <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Notificaciones <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="notification.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Registrar</a>
                        </li>
                        <li>
                            <a href="list_notification.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Modificar/Eliminar</a>
                        </li>
                    </ul>
                </li>
            
            
            <?php endif; ?>

            <?php if ($id_cargo_usuario == 2 || $id_cargo_usuario == 3 || $id_cargo_usuario == 4): // Cargo 2, 3 y 4 ?>
                <li>
                    <a href="home.php">
                        <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Notificaciones
                    </a>
                </li>
                <li>
                    <a href="map.php">
                        <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Mapa
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($id_cargo_usuario == 3 ): // Cargo 3 ?>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Informes Auditoria <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="report_user.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Usuarios</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Informes Usuarios <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="list_users_info.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Informaciones</a>
                        </li>
                        <li>
                            <a href="map_count.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Zonas</a>
                        </li>
                        <li>
                            <a href="graf_inst.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Empresas</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Estaciones <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="register_station.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Registrar</a>
                        </li>
                        <li>
                            <a href="list_station.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Modificar/Eliminar</a>
                        </li>
                    </ul>
                </li>
            <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Notificaciones <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="notification.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Registrar</a>
                        </li>
                        <li>
                            <a href="list_notification.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i> Modificar/Eliminar</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            
            <?php if ($id_cargo_usuario == 4): // Cargo 4 ?>
                <li>
                    <a href="#!" class="btn-sideBar-SubMenu">
                        <i class="zmdi zmdi-case zmdi-hc-fw"></i> Informes Usuarios <i class="zmdi zmdi-caret-down pull-right"></i>
                    </a>
                    <ul class="list-unstyled full-box">
                        <li>
                            <a href="list_users_info.php"><i class="zmdi zmdi-timer zmdi-hc-fw"></i> Informaciones</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</section>
