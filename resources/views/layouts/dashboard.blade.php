<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Management | Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
   Ionicons -->

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" id="app">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#modelId"> <i class="fas fa-power-off  text-danger  "></i></a>
                </li>
            </ul>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><i class="fas fa-lock  mr-3  "></i> <strong>End session</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="container-fluid">
                                <strong class="text-info">Are you sure you want to logout ?</strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">No,close</button>
                            <button type="submit" class="btn btn-danger">Yes,Logout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Logout modal -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="" class="brand-link">
                <img src="{{asset('imgs/OR.svg')}}" alt="Logo" class="brand-image" style="opacity: .9; width: 3rem;">
                <span class="font-weight-light text-sm">Order Management</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset('imgs/avatar.png')}}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <router-link to="/profile" class="nav-link nav-link--2" >{{Auth::user()->name}}</router-link>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
                        <li class="nav-item has-treeview menu-open">
                            <router-link to="/home" class="nav-link nav-link--2">
                                <i class="nav-icon fas fa-tachometer-alt orange"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/products" class="nav-link nav-link--2">
                                <i class="fab fa-product-hunt nav-icon blue "></i>
                                <p>
                                    Products
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item has-treeview">
                            <router-link to="/orders" class="nav-link nav-link--2">
                               <i class="fas fa-list nav-icon green "></i>
                                <p>
                                    Orders
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item has-treeview">
                            <router-link to="/suppliers" class="nav-link nav-link--2">
                                <i class="fas fa-industry nav-icon indigo   "></i>
                                <p>
                                    Suppliers
                                </p>
                            </router-link>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper cntnt">

            <router-view></router-view>
            <vue-progress-bar></vue-progress-bar>

        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="http://cagimoto.ml">Order Management</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.1
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>


    </script>
</body>

</html>