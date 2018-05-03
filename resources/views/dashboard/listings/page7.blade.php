@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">What's Close By?</div>

                <div class="panel-body">
                   Lots of text and instructions and stuff. 
                   <form class="form-horizontal" method="POST" action="{{ route('create-listing') }}">
                        {{ csrf_field() }}
                       
                        
                     
                     WAITING FOR FE COMPLETION... 
                     

                     
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
