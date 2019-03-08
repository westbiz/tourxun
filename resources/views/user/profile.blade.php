@auth
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

@endauth
{{-- {{ dd($user) }} --}}