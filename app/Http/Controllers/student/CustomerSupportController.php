<?php
namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\FileUploader;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerSupportController extends Controller
{

    public function support_ticket_index()
    {
        if (in_array(auth()->user()->role, ['student', 'instructor'])) {
            $page_data['tickets'] = Ticket::where('user_id', auth()->id())->paginate(10);
        } else {
            $page_data['tickets'] = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10); // admin hole empty result
        }

        $view_path = 'frontend.' . get_frontend_settings('theme') . '.student.customer_support.index';
        return view($view_path, $page_data);
    }

    public function support_ticket_create()
    {
        $view_path = 'frontend.' . get_frontend_settings('theme') . '.student.customer_support.create';
        return view($view_path, [
            'categories' => TicketCategory::where('status', 1)->orderBy('title')->get(),
            'priorities' => TicketPriority::where('status', 1)->orderBy('id')->get(),
        ]);
    }

    public function support_ticket_store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:160'],
            'category_id' => ['required', 'exists:ticket_categories,id'],
            'priority_id' => ['required', 'exists:ticket_priorities,id'],
            'message' => ['required', 'string', 'max:10000'],
            'file.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $adminId = User::where('role', 'admin')->orderBy('id')->value('id');
        $statusId = TicketStatus::where('status', 1)->orderByDesc('default_view')->orderBy('id')->value('id');

        abort_unless($adminId && $statusId, 422, 'O suporte ainda não foi configurado.');

        $ticket['subject']     = $validated['subject'];
        $ticket['code']        = Str::upper(Str::random(8));
        $ticket['creator_id']  = $adminId;
        $ticket['user_id']     = auth()->user()->id;
        $ticket['status_id']   = $statusId;
        $ticket['priority_id'] = $validated['priority_id'];
        $ticket['category_id'] = $validated['category_id'];

        $ticket_id = Ticket::insertGetId($ticket);

        $insert_info = Ticket::find($ticket_id);

        $paths = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $paths[] = FileUploader::upload($file, 'uploads/ticket_files');
            }
        }

        if (! empty($paths)) {
            $message['file'] = json_encode($paths);
        }

        $message['ticket_thread_code'] = $insert_info->code;
        $message['message']            = $validated['message'];
        $message['sender_id']          = auth()->user()->id;
        $message['receiver_id']        = $adminId;

        TicketMessage::create($message);

        return redirect()->route('support.ticket.message', $insert_info->code)->with('success', 'Chamado aberto com sucesso.');
    }

    public function support_ticket_message($ticket_thread_code = '')
    {

        $page_data['ticket_details'] = Ticket::where('code', $ticket_thread_code)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        $page_data['conversation']   = TicketMessage::where('ticket_thread_code', $ticket_thread_code)->get();

        $view_path = 'frontend.' . get_frontend_settings('theme') . '.student.customer_support.ticket_details';
        return view($view_path, $page_data);

    }

    public function support_ticket_message_store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message'            => $request->file('file') ? 'nullable|string' : 'required|string',
            'sender_id'          => 'required|integer|exists:App\Models\User,id',
            'receiver_id'        => 'required|integer|exists:App\Models\User,id',
            'ticket_thread_code' => 'required|string|exists:tickets,code',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Escreva uma mensagem ou anexe um arquivo.');
        }

        $ticket = Ticket::where('code', $request->ticket_thread_code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_unless((int) $request->sender_id === auth()->id() && (int) $request->receiver_id === (int) $ticket->creator_id, 403);
        $data = [
            'message'            => $request->message,
            'sender_id'          => $request->sender_id,
            'receiver_id'        => $request->receiver_id,
            'ticket_thread_code' => $request->ticket_thread_code,
            'created_at'         => date('Y-m-d H:i:s'),
        ];

        $paths = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $paths[] = FileUploader::upload($file, 'uploads/ticket_files');
            }
        }

        if (! empty($paths)) {
            $data['file'] = json_encode($paths);
        }

        $data['updated_at'] = now();
        $data['created_at'] = now();
        TicketMessage::insert($data);

        Session::flash('success', 'Mensagem enviada.');
        return redirect()->back();
    }

}
