<!DOCTYPE html>
<html lang="en">
    <head>
        @include('errors._components.metas') 
        @include('errors._components.styles')
    </head>
    <body>
        <div id="app">
            <section class="section">
                <div class="container mt-5">
                    <div class="page-error">
                        <div class="page-inner">
                            @yield('content')
                        </div>
                    </div>
                    @include('errors._components.footer')
                </div>
            </section>
        </div>
    </body>
    @include('errors._components.scripts')
</html>