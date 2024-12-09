<!DOCTYPE html>
<html lang="en">
    <head>
        @include('portal._components.metas') 
        @include('portal._components.styles')
        @yield('page-styles')
    </head>
    <body class="layout-3">
        <div id="app">
            <div class="main-wrapper container">
                @include('portal._components.web-topbar')
                <div class="main-content" style="min-height: 647px;">
                    <section class="section">
                        @yield('content')
                    </section>
                    @include('portal._components.footer')
                </div>
            </div>
        </div>
    </body>
    @yield('chart-scripts')
    @include('portal._components.scripts')
    @yield('page-scripts')
</html>