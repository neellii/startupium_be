<div class="c-avatar"><img class="c-avatar-img"
                           src="{{$user->getAvatarUrl()}}"
                           alt="{{$user->firstname}}">
    <span class="c-avatar-status {{$user->isOnline() ? 'bg-success' : 'bg-danger'}}"></span>
</div>
