<!DOCTYPE html>
<html>

<head>
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Blog Portal">
    <!-- Title -->
    <title>Blog Portal</title>
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="/assets/plugins/iconfonts/icons.css" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="/assets/css/main.css" rel="stylesheet">
    <!---Internal  Prism css-->
    <link href="/assets/plugins/prism/prism.css" rel="stylesheet">
    <link href="/assets/plugins/treeview-prism/prism.css" rel="stylesheet">
    <link href="/assets/plugins/treeview-prism/prism-treeview.css" rel="stylesheet">
    <link href="/assets/css/themes/all-themes.css" rel="stylesheet" />
    <link href="/custom.css" rel="stylesheet" />
    @stack('css')
	@vite('resources/css/app.css')
</head>

<body class="theme-blush">
    <div id="app">
        <div class="color-bg">
            <div class="sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="javascript:void(0);"><i class="fe fe-menu"></i></a>
            </div>
            <ul class="nav navbar-nav">
                <li class="nav-item"><a class="btn btn-danger text-white mr-3 btn-header" href="https://spruko.com/licenses.html" target="_blank"><i class="icon-bag3"></i>LogOut</a></li>
            </ul>
        </div>
    
        <section class="d-flex">
            @include('layout.sidebar')
    
            <div class="main-section">
                @yield('section')
            </div>
        </section>
    </div>
    @stack('scripts')
	@vite('resources/js/app.js')
</body>

</html>
