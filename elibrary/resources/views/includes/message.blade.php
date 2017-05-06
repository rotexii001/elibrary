@if(count($errors)>0)

    @foreach($errors->all() as $error)
        <div style="margin-bottom:3px; font-weight:bold"><span class="alert alert-danger no-border">{{ $error }}</span></div>
    @endforeach

@endif

@if(Session::has('message'))
	<div style="font-weight:bold; margin-bottom:3px"><span class="alert-success">{{Session::get('message')}}</span></div>
@endif
