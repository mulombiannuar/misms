@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-warning card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    @foreach ($sections as $section)
                        <li class="nav-item"><a class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}"
                                href="#section-{{ $section->section_id }}" data-toggle="tab"><i class="fa fa-list"></i>
                                Form {{ strtoupper($section->section_numeric . $section->section_name) }}</a></li>
                    @endforeach
                    <li class="nav-item"><a class="nav-link" href="#overall" data-toggle="tab"><i class="fa fa-users"></i>
                            Class Performance</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($sections as $section)
                        <div class="tab-pane {{ $loop->iteration == 1 ? 'active' : '' }}"
                            id="section-{{ $section->section_id }}">
                            <!-- Section -->
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fa fa-list"></i>
                                        Form {{ strtoupper($section->section_numeric . $section->section_name) }}</h3>
                                </div>
                                <div class="card-body">
                                    {{ strtoupper($section->section_numeric . $section->section_name) }}
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.Section -->
                        </div>
                        <!-- /.tab-pane -->
                    @endforeach

                    <div class="tab-pane" id="overall">
                        <!-- documents -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-users"></i> Class Performance</h3>
                            </div>
                            <div class="card-body">
                                Class Performance
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- documents -->
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </x-section>
    <!-- /.section component -->
@endsection
