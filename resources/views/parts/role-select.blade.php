<div class="radio">
    @foreach($roles as $rl)
    <label class="radio-inline" for="user_{{$userid=$user ? $user->id : 'new'}}_role_{{$rl->id}}">
        <input type="radio"
            name="user_{{$userid}}"
            id="user_{{$userid}}_role_{{$rl->id}}"
            value="{{$rl->id}}"
            @if ($user)
                @if ($user->inRole($rl->slug))
                    checked="true"
                @endif
            @elseif (old('user_'.$userid.'_role_'.$rl->id) == "on")
                checked="true"
            @endif
            >
        {{$rl->name}}
    </label>
    @endforeach
</div>
