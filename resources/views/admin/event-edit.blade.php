@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Event</h1>
@stop

@section('content')
    <div class="card card-primary">
        <a class="btn btn-success" href="{{ url('admin') }}">Повернутися до календаря</a>

        <div class="card-header">
            <h3 class="card-title">Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        @if (session()->has('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif
        <form role="form" method="post" action="{{ route('save.event.info', ['id' => $event->id]) }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    @if($event->image)
                        <img src="{{ asset('storage/'.$event->image) }}" alt="" style="max-width: 200px">
                        <br>
                    @endif
                    <label for="exampleInputEmail1">Image</label>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile1">
                        <label class="custom-file-label" for="exampleInputFile1">Choose file</label>
                    </div>
                </div>
                <div class="form-group">
                    @if($event->file)
                        <a href="{{ asset('storage/'.$event->file) }}" target="_blank">file</a>
                        <br>
                    @endif
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Текст до ивента</label>
                    <textarea class="form-control" rows="3" placeholder="Напішіть ..." name="text">{{ $event->text }}</textarea>
                </div>
                <div class="form-group">

                <div class="input-group">
                    <label>Дата початку</label><br>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" value="{{ $event->start_at }}">
                </div>
                </div>
                <div class="form-group">

                <div class="input-group">
                    <label>Дата закінчення</label><br>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false" value="{{ $event->end_at }}">
                </div>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')

@stop
