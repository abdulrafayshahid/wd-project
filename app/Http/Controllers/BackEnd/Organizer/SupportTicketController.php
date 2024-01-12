<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\Event\Ticket;
use App\Models\Event;
use App\Models\Event\EventContent;
use App\Models\Event\EventDates;
use App\Models\Organizer;
use App\Models\BasicSettings\MailTemplate;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\SupportTicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Mews\Purifier\Facades\Purifier;
use DB;
class SupportTicketController extends Controller
{
    //index
    public function index(Request $request)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }

        $status = $ticket_id = null;
        if ($request->filled('status')) {
            $status = $request['status'];
        }
        if ($request->filled('ticket_id')) {
            $ticket_id = $request['ticket_id'];
        }



        $collection = SupportTicket::where([['user_id', Auth::guard('organizer')->user()->id], ['user_type', 'organizer']])->when($status, function ($query, $status) {
            return $query->where('status',  $status);
        })
            ->when($ticket_id, function ($query, $ticket_id) {
                return $query->where('id', 'like', '%' . $ticket_id . '%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('organizer.support_ticket.index', compact('collection'));
    }
    public function index_manual(Request $request)
    {
        $user = Organizer::where('id',Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
            $tickets = Ticket::where('manual_ticket', 1)
            ->where('organizer_id', Auth::guard('organizer')->user()->id)
            ->paginate(10);
        return view('organizer.manual_ticket.index', compact('tickets'));
    }
    //create
    public function create()
    {

        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }
        return view('organizer.support_ticket.create');
    }
    public function create_manual()
    {
        $user = Organizer::where('id',Auth::guard('organizer')->user()->id)->first('created_by')->created_by;

       
        if(empty($user)){
            $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
            ->where('events.organizer_id', Auth::guard('organizer')->user()->id)
            ->where('events.is_featured','yes')->where('events.admin_approval', 1)->where('events.status', 1)
            ->groupBy('events.id')
            ->select('event_contents.event_id as event_id','title')
            ->get();

        }else{

            $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
            ->where('events.organizer_id', $user)
            ->where('events.is_featured','yes')->where('events.admin_approval', 1)->where('events.status', 1)
            ->groupBy('events.id')
            ->select('event_contents.event_id as event_id','title')
            ->get();
        }
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }    
        return view('organizer.manual_ticket.create',compact('events'));
    }
    //store
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
        ];

        if ($request->hasFile('attachment')) {
            $rules['attachment'] = 'mimes:zip';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $in = $request->all();
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = uniqid() . '.' . $attachment->getClientOriginalExtension();
            @mkdir(public_path('assets/admin/img/support-ticket/attachment/'), 0775, true);
            $attachment->move(public_path('assets/admin/img/support-ticket/attachment/'), $filename);
            $in['attachment'] = $filename;
        }
        $in['user_id'] = Auth::guard('organizer')->user()->id;
        $in['user_type'] = 'organizer';
        SupportTicket::create($in);

        Session::flash('success', 'Support Ticket Created Successfully..!');
        return back();
    }

    public function store_manual(Request $request)
    {
        $rules = [
            'event_id' => 'required',
            'customer_email' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
        ];

     

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $in = $request->all();
        $user = Organizer::where('id',Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
        if($user == ""){
            $in['user_id'] = Auth::guard('organizer')->user()->id;
        }else{
            $in['user_id'] = $user;
        }
       
        $in['manual_ticket'] = 1;
        $in['user_type'] = 'organizer';
        Ticket::create($in);

        Session::flash('success', 'Support Ticket Created Successfully..!');
        return redirect()->route('organizer.manual.ticket');
    }
    //message
    public function message($id)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }
        $ticket = SupportTicket::where('id', $id)->firstOrFail();
        if ($ticket->user_id != Auth::guard('organizer')->user()->id) {
            return redirect()->route('organizer.dashboard');
        }
        return view('organizer.support_ticket.messages', compact('ticket'));
    }
    public function zip_file_upload(Request $request)
    {
        $file = $request->file('file');
        $allowedExts = array('zip');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {
                    $ext = $file->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only zip file supported");
                    }
                },
                'max:20000'
            ],
        ];

        $messages = [
            'file.max' => ' zip file may not be greater than 5 MB',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            @mkdir(public_path('assets/front/temp/'), 0775, true);
            $file->move(public_path('assets/front/temp/'), $filename);
            $input['file'] = $filename;
        }

        return response()->json(['data' => 1]);
    }
    public function ticketreply(Request $request, $id)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }
        $file = $request->file('file');
        $allowedExts = array('zip');
        $rules = [
            'reply' => 'required',
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {

                    $ext = $file->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only zip file supported");
                    }
                },
                'max:20000'
            ],
        ];

        $messages = [
            'file.max' => ' zip file may not be greater than 5 MB',
        ];

        $request->validate($rules, $messages);
        $input = $request->all();

        $reply = $request->reply;
        $input['reply'] = Purifier::clean($reply, 'youtube');
        $input['type'] = 3;
        $input['user_id'] = Auth::guard('organizer')->user()->id;

        $input['support_ticket_id'] = $id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            @mkdir(public_path('assets/admin/img/support-ticket/'), 0775, true);
            $file->move(public_path('assets/admin/img/support-ticket/'), $filename);
            $input['file'] = $filename;
        }

        $data = new Conversation();
        $data->create($input);

        $files = glob('assets/front/temp/*');
        foreach ($files as $file) {
            unlink($file);
        }

        SupportTicket::where('id', $id)->update([
            'last_message' => Carbon::now(),
            'status' => 2,
            'user_id' => Auth::guard('organizer')->user()->id
        ]);

        Session::flash('success', 'Message Sent Successfully');
        return back();
    }

    //delete
    public function delete($id)
    {
        //delete all support ticket
        $support_ticket = SupportTicket::where([['user_id', Auth::guard('organizer')->user()->id], ['user_type', 'organizer'], ['id', $id]])->first();
        if ($support_ticket) {
            //delete conversation 
            $messages = $support_ticket->messages()->get();
            foreach ($messages as $message) {
                @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                $message->delete();
            }
            @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
            $support_ticket->delete();
        }
        Session::flash('success', 'Manual Ticket Deleted Successfully..!');
        return back();
    }
    public function delete_manual($id)
    {
       $ticket = Ticket::find($id);
       $ticket->delete();
        Session::flash('success', 'Manual Ticket Deleted Successfully..!');
        return back();
    }
    public function bulk_delete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $support_ticket = SupportTicket::where([['user_id', Auth::guard('organizer')->user()->id], ['user_type', 'organizer'], ['id', $id]])->first();
            if ($support_ticket) {
                //delete conversation 
                $messages = $support_ticket->messages()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
                $support_ticket->delete();
            }
        }
        Session::flash('success', 'Support Tickets are Deleted Successfully..!');
        return Response::json(['status' => 'success'], 200);
    }
    public function edit_manual($id)
    {
        $user = Organizer::where('id',Auth::guard('organizer')->user()->id)->first('created_by')->created_by;

       
        if($user == ""){
            $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
            ->where('events.organizer_id', Auth::guard('organizer')->user()->id)
            ->where('events.is_featured','yes')->where('events.admin_approval', 1)->where('events.status', 1)
            ->groupBy('events.id')
            ->select('event_contents.event_id as event_id','title')
            ->get();

        }else{
            $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
            ->where('events.organizer_id', $user)
            ->where('events.is_featured','yes')->where('events.admin_approval', 1)->where('events.status', 1)
            ->groupBy('events.id')
            ->select('event_contents.event_id as event_id','title')
            ->get();
        }
        
        $ticket = Ticket::find($id);
    
        return view('organizer.manual_ticket.edit',compact('events','ticket'));
    }
    public function update_manual(Request $request,$id)
    {
        $rules = [
            'event_id' => 'required',
            'customer_email' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
        ];

     

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $in = $request->all();
        $user = Organizer::where('id',Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
        if($user == ""){
            $in['user_id'] = Auth::guard('organizer')->user()->id;
        }else{
            $in['user_id'] = $user;
        }
       
        $in['manual_ticket'] = 1;
        $in['user_type'] = 'organizer';
        Ticket::find($id)->update($in);
        Session::flash('success', 'Manual Ticket Update Successfully..!');
        return redirect()->route('organizer.manual.ticket');
    }
    public function bulk_delete_manual(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            Ticket::find($id)->delete();
        }
        Session::flash('success', 'Manual Tickets are Deleted Successfully..!');
        return Response::json(['status' => 'success'], 200);
    }
    public function sendmail_manual(Request $request,$id)
    {
        $ticket = Ticket::find($id);


        $event = Event::where('id', $ticket->event_id)->first();
        $event_content = EventContent::where('event_id', $ticket->event_id)->first();

           // first get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'Manual_ticket')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp information from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();
      $token = "";
    $link = '<a href=' . url("customer/signup-verify/" . $token) . '>Click Here</a>';

    $mailBody = str_replace('{customer_name}', $ticket->customer_name, $mailBody);
    $mailBody = str_replace('{event_name}', $event_content->title, $mailBody);
    $mailBody = str_replace('{event_start_date}', $event->start_date, $mailBody);
    $mailBody = str_replace('{event_end_date}', $event->end_date, $mailBody);
    $mailBody = str_replace('{event_address}', $event_content->address, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
        try {
          $mail->isSMTP();
          $mail->Host       = $info->smtp_host;
          $mail->SMTPAuth   = true;
          $mail->Username   = $info->smtp_username;
          $mail->Password   = $info->smtp_password;
    
        //   if ($info->encryption == 'TLS') {
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        //   }
    
          $mail->Port       = $info->smtp_port;
        } catch(\Exception $e) { 
            // die($e->getMessage());
        }
    }

    // finally add other informations and send the mail
    try {
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($ticket->customer_email);

      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail->send();

      
    } catch (\Exception $e) {
        // die($e->getMessage());
      Session::flash('error', 'Mail could not be sent!');
    }


        Session::flash('success', 'Mail Send Successfully..!');
        return redirect()->route('organizer.manual.ticket');
    }
    
    public function ticket_view_manual($id)
    {
        $user = Organizer::where('id',Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
        $ticket = Ticket::find($id);

            $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
            ->where('events.is_featured','yes')->where('events.admin_approval', 1)->where('events.status', 1)
            ->where('events.id',$ticket->event_id)
            ->groupBy('events.id')
            ->select('event_contents.event_id as event_id','title','address','start_date','end_date')
            ->first();
        return view('organizer.manual_ticket.view',compact('events','ticket'));
    }
}
