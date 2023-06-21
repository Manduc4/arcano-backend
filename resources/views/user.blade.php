<h1>
  User
</h1>
<h1>
  {{ route('user.show', 2)}}
</h1>
<h1>
  {{ $name }}
</h1>
<h1>
  {{ $user->name }}
</h1>
@if($user->name === 'Dr. Jensen Pouros')
  {{ $user->name }}
@elseif()
@endif
