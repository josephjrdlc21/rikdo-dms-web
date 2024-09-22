<!DOCTYPE html>
<html lang="en">
    <head>
        @include('portal._components.metas') 
        @include('portal._components.styles')
        @yield('page-styles')
    </head>
    <body>
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
                @include('portal._components.topbar')
                @include('portal._components.sidebar')
                <div class="main-content">
                    <section class="section">
                        @yield('breadcrumb')
                        @yield('content')
                    </section>
                </div>
                @include('portal._components.footer')
            </div>
        </div>
    </body>
    @include('portal._components.scripts')
    @yield('page-scripts')
</html>