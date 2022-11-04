@extends('layouts.app.dashboard')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('layouts.app.incls.page_header')
        <!-- Main content -->
        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-list"></i> Dashboard Items</h3>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <h1>Admin Dashboard</h1>
                    </div><!-- /.container-fluid -->
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
