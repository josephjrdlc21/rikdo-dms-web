<!DOCTYPE html>
<html lang="en">
    <head>
        @include('web._components.metas') 
        @include('web._components.styles')
        @yield('page-styles')
    </head>
    <body class="layout-3">
        <div id="app">
            <div class="main-wrapper container">
                @include('web._components.topbar')
                <div class="main-content" style="min-height: 647px;">
                    <section class="section">
                        @yield('content')
                    </section>
                    @include('web._components.footer')
                </div>
            </div>
        </div>
    </body>
    @include('web._components.scripts')
</html>