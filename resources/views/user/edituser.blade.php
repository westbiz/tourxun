@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


<form method="POST" action="{{route('user.store')}}">
    {{csrf_field()}}
  <div class="form-group">
    <label for="username">name</label>
    <input type="text" class="form-control" id="username" aria-describedby="emailHelp" placeholder="Enter name" name="name" value="{{ Auth::user()->name }}">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="age">Age</label>
    <input type="email" class="form-control" id="age" placeholder="Age" name="age" value="{{ Auth::user()->profile->age }}">
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



                </div>
            </div>
        </div>
    </div>
</div>
@endsection