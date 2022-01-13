<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Invitation;
use App\Models\User;

class InvitationController extends Controller
{
    //Here we are taking all the status for invition that any Invited User can change. 
    private $valid_invitation_status = array('accept', 'decline');
    

    /*
    |--------------------------------------------------------------------------
    | Send Invitation API 
    |--------------------------------------------------------------------------
    |
    | This is API that would be use to send an Invitation.
    | We need two parameter(both are required): 
    | sender: User Id of the User who is sending invitation.
    | invited: User Id of the Invited User 
    */
    function add(Request $req)
    {
        $rules = array(
            'sender' => 'required|numeric|min:0|not_in:0',
            'invited' => 'required|numeric|min:0|not_in:0'
        );
        $validator = Validator::make($req->all(), $rules);
        if($validator->fails())
        {
            return response()->json(["result"=>"failure", "message"=>$validator->errors()], 401);
        }else{ 
            $sender = User::find($req->sender);
            $invited = User::find($req->invited);
            if(isset($sender->id) && isset($invited->id))
            {
                $invitation = Invitation::where([
                                                    ['sender', '=', $req->sender],
                                                    ['invited', '=', $req->invited],
                                                    ['status', '=', 'sent']
                                                ])->get();
                if(count($invitation) == 0)  
                {                            
                    $invitation = new Invitation;
                    $invitation->sender = $req->sender;
                    $invitation->invited = $req->invited;
                    $result = $invitation->save(); 
                    if($result)
                    {
                        return response()->json(["result"=>"success", "message"=>"success"], 200);
                    }else{
                        return response()->json(["result"=>"failure", "message"=>"Due to some error, we are unable to process your request."], 201);
                    }
                }else{
                    return response()->json(["result"=>"failure", "message"=>"You have already sent an invitation request earlier."], 201); 
                }
            }else{
                return response()->json(["result"=>"failure", "message"=>"Either sender or invited user does not exist."], 401);
            }
            
        }
        
    }
    
    /*
    |--------------------------------------------------------------------------
    | Respond Invitation API 
    |--------------------------------------------------------------------------
    |
    | This is API that would be use while a Invited User try to accept/decline an invitation.
    | We need three parameter(all are required): 
    | invitation_id: Id of the invitation in database table (invitations)
    | invited: User Id of the Invited User 
    | status: accept/decline 
    */

    function respondinvitation(Request $req)
    {
        $rules = array(
            'invitation_id' => 'required|numeric|min:0|not_in:0',
            'invited' => 'required|numeric|min:0|not_in:0',
            'status' => 'required'
        );
        $validator = Validator::make($req->all(), $rules);
        if($validator->fails())
        {
            return response()->json(["result"=>"failure", "message"=>$validator->errors()], 401);
        }else{ 
            $user = User::find($req->invited); 
            if(isset($user->id))
            {
                $invitation = Invitation::find($req->invitation_id);
                if($invitation->invited == $req->invited && $invitation->status == 'sent' && in_array($req->status, $this->valid_invitation_status))
                {
                    $invitation->status = $req->status;
                    $result = $invitation->save(); 
                    if($result)
                    {
                        return response()->json(["result"=>"Success", "message"=>"success"], 200);
                    }else{
                        return response()->json(["result"=>"failure", "message"=>"Due to some error, we are unable to process your request."], 201);
                    }
                }else{
                    return response()->json(["result"=>"Failure", "message" => "Wrong invitation Id"], 401);
                }
            }else{
                return response()->json(["result"=>"Failure", "message" => "Invited user does not exist."], 401);
            }
           
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Decline Invitation API 
    |--------------------------------------------------------------------------
    |
    | This is API that would be use while a Sender User try to cancel an invitation, which is not responded by Invited User Yet.
    | We need two parameter(both are required): 
    | invitation_id: Id of the invitation in database table (invitations)
    | sender: User Id of the Sender User 
    | 
    */

    function declineinvitation(Request $req)
    {
        $rules = array(
            'invitation_id' => 'required|numeric|min:0|not_in:0',
            'sender' => 'required|numeric|min:0|not_in:0'
        );
        $validator = Validator::make($req->all(), $rules);
        if($validator->fails())
        {
            return response()->json(["result"=>"failure", "message"=>$validator->errors()], 401);
        }else{
            $user = User::find($req->sender);
            if(isset($user->id))
            {
                $invitation = Invitation::find($req->invitation_id);
                if($invitation->sender == $req->sender && $invitation->status == 'sent')
                {
                    $invitation->status = 'cancel';
                    $result = $invitation->save(); 
                    if($result)
                    {
                        return response()->json(["result"=>"Success", "message"=>"success"], 200);
                    }else{
                        return response()->json(["result"=>"failure", "message"=>"Due to some error, we are unable to process your request."], 201);
                    }
                }else{
                    return response()->json(["result"=>"Failure", "message" => "Unable to process the request."], 401);
                }
            }else{
                return response()->json(["result"=>"Failure", "message" => "Sender user does not exist."], 401);
            }
           
        }
    }
    
}
