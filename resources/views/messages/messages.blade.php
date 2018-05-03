@extends('layouts.app')

@section('content')
<section id="dash">
	<div class="grid">
		<div class="content rv">		
			<div class="cookie-crisp"><a href="">Dashboard <i class="fas fa-chevron-right"></i></a>  Your Page Title</div>	
			
			<h1 class="h6">Your Messages</h1>
            <pre>
            <!--{{ var_dump($threads) }} -->
            </pre>
              @if(!isset($messages))
                <p>You do not have any messages.</p>
              @else
            @foreach($threads as $thread)
            <?php 
                $most_recent = end($thread); 
                $count = count($thread);
            ?>

        <div class="messages-user">
		<div class="indiv-message-con">
			<div class="grid">
				<div class="img" style="background-image: url(/img/vw-rv.jpg);"></div>
				<div class="message-preview">
					<p class="name-of-user h8">{{ $most_recent->fromName }} <span class="h13">{{ $most_recent->message }}</span></p>
				</div>
				
				<div class="details-message">
					<p class="latest-date h12">{{ $most_recent->createdTimeAgo() }}</p>
					<span class="notification">{{ $count }}</span>
					<a class="expand-message h12">Expand <img src="/img/down.svg"></a>
				</div>
			</div>
			<div class="convo-thread" style="display: none;">
				<div class="all-messages">
                    @foreach($thread as $message)
					<div @if($message->to != Auth::user()->id) class="current" @endif>
						<p>{{ $message->message }}</p>
						@if($message->to == Auth::user()->id)
                            <div class="them"><div class="img-off" style="background-image: url(/img/vw-rv.jpg);"></div><p>{{ $message->fromName }}</p></div>
                        @else
                            <div class="me"><div class="img-off" style="background-image: url(/img/vw-rv.jpg);"></div><p>You</p></div>                        
                        @endif
					</div>
					@endforeach
				</div>
				
				<div class="respond">
                    
					<form class="style" method="POST" action="{{ route('reply') }}">
                        {{ csrf_field() }}
                        @if ($errors->has('message-'.$most_recent->thread))
                            <span class="help-block">
                                <strong>{{ $errors->first('message-'.$most_recent->thread) }}</strong>
                            </span>
                        @endif
						<textarea id="message-{{ $most_recent->thread }}" name="message-{{ $message->thread }}" placeholder="This is my response..." required>
                             {{ old('message-'.$most_recent->thread) }}
                        </textarea>
                      @if($most_recent->to == Auth::user()->id)
                        <input type="hidden" name="to" value="{{ $most_recent->from }}" />
                      @else
                        <input type="hidden" name="to" value="{{ $most_recent->to }}" />
                      @endif
                        <input type="hidden" name="thread" value="{{ $most_recent->thread }}" />
                        <button type="submit" class="button listing">
                                    Reply
                        </button>
					</form>
				</div>
				
				<a class="collapse">Collapse</a>
			</div>
		</div>
	</div>
      @endforeach
      @endif

		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection
