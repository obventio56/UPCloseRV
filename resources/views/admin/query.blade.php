@extends('layouts.app')

@section('content')

<section id="admin-dash">
	<div class="grid">
		<div class="content">
            
            <p class="h6 title">Query Database</p>
            <form class="style" method="POST" action="{{ route('run-query') }}">
                {{ csrf_field() }}
                @if ($errors->has('query'))
                    <span class="help-block">
                        <strong>{{ $errors->first('query') }}</strong>
                    </span>
                @endif
                <textarea id="query" placeholder="SELECT * FROM users;" name="query" required></textarea>
                <button type="submit" class="button white">Run</button>
            </form>
            
             @if(isset($results))
            <div class="panel panel-default">
                <div class="panel-heading">MySQL Error</div>

                <div class="panel-body">

                    {{ $results }}

                </div>
            </div>
            @endif
            
            
            
            

		</div>
		
		
      	@component('components.sidebars.admin')
		
		@endcomponent  
	</div>
</section>

<!--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">SQL Query</div>

                <div class="panel-body">
                   Enter the desired query in the box below. Only select queries are enabled. 
                   <form class="form-horizontal" method="POST" action="{{ route('run-query') }}">
                        
                       
                      <div class="form-group{{ $errors->has('query') ? ' has-error' : '' }}">
                            <label for="query" class="col-md-4 control-label"></label>

                            <div class="col-md-6">
                                <textarea id="query" class="form-control" name="query" required></textarea>

                                @if ($errors->has('query'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('query') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Run
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if(isset($results))
            <div class="panel panel-default">
                <div class="panel-heading">MySQL Error</div>

                <div class="panel-body">

                    {{ $results }}

                </div>
            </div>
            @endif
        </div>
    </div>
</div>-->
@endsection
