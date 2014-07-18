<!DOCTYPE html>
<html lang="en">
    <head>
        @include('html.meta', array('title' => 'Home'))
    </head>

    <body>

        <!-- Start Fixed navbar -->
        @include('html.navbar')
        <!-- End Fixed navbar -->


        <div id="cl-wrapper">

            @include('html.sidebar')

            <div class="container-fluid" id="pcont">
                <div class="page-head">
                    <h2>Blank Page</h2>
                    <ol class="breadcrumb">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Category</a></li>
                        <li class="active">Sub Category</li>
                    </ol>
                </div>
                <div class="cl-mcont">
                    <h3 class="text-center">Content goes here!</h3>
                </div>
            </div>

        </div>

        @include('html.footer')

    </body>

</html>
