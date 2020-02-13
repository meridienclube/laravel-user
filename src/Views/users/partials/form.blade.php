<div class="row">

    <div class="col-4">
        <div class="form-group">
            <label class="form-label">{{ __('user::views.name') }} <span class="required"> * </span></label>
            {!! Form::text('name', isset($user->name)? $user->name : NULL, ['class' => 'form-control', 'placeholder' => __('user::views.name')]) !!}
        </div>
    </div>

    <div class="col-5">
        <div class="form-group">
            <label class="form-label">{{ __('user::views.email') }} <span class="required"> * </span></label>
            {!! Form::text('email', isset($user->email)? $user->email : NULL, ['class' => 'form-control', 'placeholder' => __('user::views.email')]) !!}
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <label class="">{{ __('user::views.status') }} <span class="required"> * </span></label>
            {{ Form::select('status_id', $statuses, isset($user) ? $user->status_id : null, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <label class="form-label">{{ __('user::views.password') }} <span class="required"> * </span></label>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('user::views.password')]) !!}
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <label class="form-label">{{ __('user::views.password_confirmation') }} <span class="required"> * </span></label>
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => __('user::views.password_confirmation')]) !!}
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <label class="form-label">{{ __('user::views.avatar') }}</label>
            {!! Form::file('avatar', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <label class="form-label">{{ __('user::views.roles') }} <span class="required"> * </span></label>
            {{ Form::select('sync[roles][]', $roles, isset($user) ? $user->roles()->pluck('id') : null, ['class' => 'form-control', 'multiple' => true]) }}
        </div>
    </div>

    @if (isset($user))
        @foreach ($user->options as $option)
            <div class="col-6">
                {!! option_input($user, $option) !!}
            </div>
        @endforeach
    @endif

    <div class="col-12">
        <div class="form-group">
            @formButtons()
            Form Buttons
            @endformButtons
        </div>
    </div>

</div>
