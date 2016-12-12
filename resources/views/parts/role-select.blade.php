<div class="radio">
    @foreach($roles as $rl)
    <label class="radio-inline" for="user_{{$userid=$user ? $user->id : 'new'}}_role_{{$rl->id}}">
        <input type="radio"
            name="user_{{$userid}}_role"
            v-model="user_{{$userid}}_role"
            id="user_{{$userid}}_role_{{$rl->id}}"
            value="{{$rl->name}}"
            @if ($user)
                @if ($user->inRole($rl->slug))
                    data-default="{{$rl->name}}"
                @endif
            @elseif (old('user_'.$userid.'_role_'.$rl->id) == "on")
                data-default="{{$rl->name}}"
            @endif
            >
        {{$rl->name}}
    </label>
    @endforeach
</div>