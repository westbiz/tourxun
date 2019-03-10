{{-- @auth
<div>用户:{{ $user->name }}</div>
<div>年龄：{{ $user->profile->age }}</div>
<div>性别：{{ $user->profile->gender }}</div>
<div>出生日期：{{ $user->profile->birthdate }}</div>
<div>城市：{{ $user->profile->cityid }}</div>
<div>手机：{{ $user->profile->mobile }}</div>
<div>
	<address>
	地址：{{ $user->profile->address }}
	</address>
</div>

@endauth --}}
{{-- {{ dd($user) }} --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    欢迎 <b>{{ Auth::user()->name }}</b> 回来！ 
                    <div>用户：{{ Auth::user()->name }}</div>
					<div>年龄：{{ Auth::user()->profile->age }}</div>
					<div>性别：{{ Auth::user()->profile->gender }}</div>
					<div>出生日期：{{ Auth::user()->profile->birthdate }}</div>
					<div>城市：{{ Auth::user()->profile->cityid }}</div>
					<div>手机：{{ Auth::user()->profile->mobile }}</div>
					<div>
						<address>
						地址：{{ Auth::user()->profile->address }}
						</address>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection