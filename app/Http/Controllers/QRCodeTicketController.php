<?php

namespace App\Http\Controllers;

use App\Models\PostTicket;
use App\Models\TicketType;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class QRCodeTicketController extends Controller
{
    public function generate_qr_codes($event_id){
        $event = auth()->user()->events()->where('event_id',$event_id)->first();
        $ticket_types = $event->ticket_types()->get();
        return view('admin.qr_codes.generate', ['ticket_types' => $ticket_types]);
    }

    public function generate_qr_codes_post(Request $request, $event_id){
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|numeric|max:200',
        ]);
        $ticket_type = TicketType::find($request->ticket_type_id);
        $event = $ticket_type->event;
        if($event->id != $event_id){
            return redirect()->back()->with('error',"Invalid Ticket Type!");
        }
        $qr_codes = [];
        for($i = 0; $i < $request->quantity; $i++){
            $qr_codes[] = PostController::generate_random_string(6);
        }
        $directory_name = date('Y-m-d') . "-" . $event->id . "-" . uniqid();
        $directory_path = storage_path('app/qrcodes/' . $directory_name);
        if (!file_exists($directory_path)) {
            mkdir($directory_path, 0777, true);
        }
        $qr_options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_JPG,
            'eccLevel'   => QRCode::ECC_L,
            'imageTransparent' => false,
            'imagickFormat' => 'jpg',
            'imageTransparencyBG' => [255, 255, 255],
        ]);
        foreach($qr_codes as $qr_code){
            $post_ticket = new PostTicket();
            $post_ticket->ticket_type_id = $ticket_type->id;
            $post_ticket->code = $qr_code;
            $post_ticket->save();
            $qrcode = new QRCode($qr_options);
            // qrcode render to storage not public
            
            $qrcode->render($qr_code, $directory_path."/" . $qr_code . ".jpg");
        }
        // zip file
        $zip_file_name = $directory_name . ".zip";
        $zip_file_path = storage_path('app/qrcodes/' . $zip_file_name);
        $zip = new \ZipArchive();
        $zip->open($zip_file_path, \ZipArchive::CREATE);
        foreach($qr_codes as $qr_code){
            $zip->addFile($directory_path."/" . $qr_code . ".jpg", $qr_code . ".jpg");
        }
        $zip->close();
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }

    public function generate_qr_tickets($event_id)
    {
        $event = auth()->user()->events()->where('event_id', $event_id)->first();
        $ticket_types = $event->ticket_types()->get();
        return view('admin.qr_codes.generate_ticket', ['ticket_types' => $ticket_types]);
    }

    public function generate_qr_tickets_post(Request $request ,$event_id){
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|numeric|max:200',
            'template' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $ticket_type = TicketType::find($request->ticket_type_id);
        $event = $ticket_type->event;
        if($event->id != $event_id){
            return redirect()->back()->with('error',"Invalid Ticket Type!");
        }

        $qr_codes = [];
        for($i = 0; $i < $request->quantity; $i++){
            $qr_codes[] = PostController::generate_random_string(6);
        }
        
        // get template image
        $template = $request->file('template');
        $template_name = $template->getClientOriginalName();
        // get image dimension
        list($width, $height) = getimagesize($template);
        // create image from template
        // check image type
        if($template->getClientOriginalExtension() == 'png'){
            $image = imagecreatefrompng($template);
        }else{
            $image = imagecreatefromjpeg($template);
        }
        $directory_name = date('Y-m-d') . "-" . $event->id . "-" . uniqid();
        $directory_path = storage_path('app/qrcodes/' . $directory_name);
        if (!file_exists($directory_path)) {
            mkdir($directory_path, 0777, true);
        }
        $qr_options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_JPG,
            'eccLevel'   => QRCode::ECC_L,
            'imageTransparent' => false,
            'imagickFormat' => 'jpg',
            'imageTransparencyBG' => [255, 255, 255],
        ]);

        foreach($qr_codes as $qr_code){
            $post_ticket = new PostTicket();
            $post_ticket->ticket_type_id = $ticket_type->id;
            $post_ticket->code = $qr_code;
            $post_ticket->save();
            $qrcode = new QRCode($qr_options);
            // qrcode render to storage not public
            $qrcode->render($qr_code, $directory_path."/" . $qr_code . ".jpg");
            // create image from qrcode
            $qrcode_image = imagecreatefromjpeg($directory_path."/" . $qr_code . ".jpg");
            // scale to 600x600
            $qrcode_image = imagescale($qrcode_image, 600, 600);
            // get qrcode image dimension
            $qrcode_height = imagesy($qrcode_image);
            $qrcode_width = imagesx($qrcode_image);
            // merge qrcode image to template image
            // add the image to bottom right corner
            imagecopymerge($image, $qrcode_image, $width - $qrcode_width, $height - $qrcode_height, 0, 0, $qrcode_width, $qrcode_height, 100);
            // save image
            imagejpeg($image, $directory_path."/" . $qr_code . ".jpg");
        }
        // zip file
        $zip_file_name = $directory_name . ".zip";
        $zip_file_path = storage_path('app/qrcodes/' . $zip_file_name);
        $zip = new \ZipArchive();
        $zip->open($zip_file_path, \ZipArchive::CREATE);
        foreach($qr_codes as $qr_code){
            $zip->addFile($directory_path."/" . $qr_code . ".jpg", $qr_code . ".jpg");
        }
        $zip->close();
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }
}
