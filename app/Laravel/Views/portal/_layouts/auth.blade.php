<!DOCTYPE html>
<html lang="en">
    <head>
        @include('portal._components.metas') 
        @include('portal._components.styles')
        @yield('page-styles')
    </head>
    <body>
        <div id="app">
            <section class="section">
                <div class="container mt-5">
                    @yield('content')
                </div>
            </section>
        </div>
    </body>
    @include('portal._components.scripts')
    @yield('page-scripts')
</html>