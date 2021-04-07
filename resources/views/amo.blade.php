@extends('layouts.app')

@section('content')
    <div class="container">
        <button type="button"
                class="btn btn-lg btn-block btn-outline-primary"
                onclick="location='{{$app_url}}'">
            Подключить AmoCRM
        </button>
    </div>
@endsection
