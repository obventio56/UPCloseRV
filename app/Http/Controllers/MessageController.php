<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidMessage;
use App\User;
use App\Models\Message;
use Auth;
use Redirect;

class MessageController extends Controller
{
    // Must be logged in to see or use any functions on this page. 
    public function __construct()
    {
        $this->middleware('auth');
    }
  
  
    // view a list of all the threads.
    public function index()
    {
      $messages = Message::where('to', Auth::user()->id)
          ->orWhere('from', Auth::user()->id)
          ->leftJoin('users as toUser', 'toUser.id', '=', 'messages.to')
          ->leftJoin('users as fromUser', 'fromUser.id', '=', 'messages.from')
          ->orderBy('thread')
          ->orderBy('created_at')
          ->get([
              'messages.*',
              'toUser.name as toName',
              'fromUser.name as fromName',
			  'toUser.traveller_photo as toUrl',
			  'fromUser.traveller_photo as fromUrl'
          ]);
        
        // reorganize ALL the things. 
        // In essence, the messages should be grouped together by thread number and sorted by date with the newest being last. 
        // However each thread needs to be sorted by the most recent message's date, the newest beinf first. 
        // So this is wonky but it works pretty well. 
        // ... 
        $buildThread = array();
        $threads = array();
        $i = 1;
        foreach($messages as $message){
            
            // This is reserved to make my life easier(and because I'm using end() in the 
            // template to grab the most recent message of each thread!
            $buildThread[0] = 'reserved'; 
            $buildThread[] = $message;
            if(!isset($messages[$i]->thread) || $message->thread !== $messages[$i]->thread){
                // This is the end of the thread
                // Set the actual value for the 0 spot for sorting purposes. 
                $buildThread[0] = $message->created_at;
                // Set the thread
                $threads[] = $buildThread;
                // reset the building thread
                $buildThread = array();
            }
            $i++;
        }
        
        // Sort the threads
        usort($threads, array($this, 'date_compare'));
        
        // Get rid of the first entry for each thread that we only added for sorting so to make my template
        // all the happier.
        foreach($threads as &$thread){
            unset($thread[0]);
        }
      
      return view('messages.messages')->with('messages', $messages)->with('threads', $threads);
    }
  
    // View new message form
    public function writeMessage($to)
    {
      $user = User::find($to); // WHAT IF the user doesn't exist? 
      
      return view('messages.write')->with('user', $user);
    }
  
    // Sending an initial message
    public function sendMessage(ValidMessage $request)
    {
      $message = new Message();
      
      $message->message = $request->message;
      $message->to = $request->to;
      $message->from = Auth::user()->id; // No one should ever be sending a message on behalf of someone else.
      $message->save();
      
      $message->thread = $message->id;
      $message->save();
      
      return Redirect::route('messages')->with('success', 'Your message has been sent!');
    }
  
    // Replying to an existing thread
    public function reply(ValidMessage $request)
    {
      $message = new Message();
      $message->message = $request->{'message-'.$request->thread};
      $message->to = $request->to;
      $message->from = Auth::user()->id; // No one should ever be sending a message on behalf of someone else. 
      $message->thread = $request->thread;
      
      $message->save();
      
      return Redirect::route('messages')->with('success', 'Your message has been sent!');
    }
    
    // Sort by dates
    protected static function date_compare($a, $b)
    {
        $t1 = strtotime($a[0]);
        $t2 = strtotime($b[0]);
        return $t2 - $t1;
    } 
}
