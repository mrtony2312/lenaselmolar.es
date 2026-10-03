<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // The markup is a legacy WPForms export: fields are posted as
        // wpforms[fields][N]. Map them to real names before validating.
        $data = $request->validate([
            'wpforms.fields.1.first' => 'required|string|max:120',
            'wpforms.fields.1.last' => 'nullable|string|max:120',
            'wpforms.fields.2' => 'required|email|max:190',
            'wpforms.fields.4' => 'nullable|string|max:200',
            'wpforms.fields.3' => 'required|string|max:5000',
        ], [], [
            'wpforms.fields.1.first' => 'nombre',
            'wpforms.fields.2' => 'correo electrónico',
            'wpforms.fields.3' => 'mensaje',
        ]);

        $fields = $data['wpforms']['fields'];
        $name = trim(($fields[1]['first'] ?? '').' '.($fields[1]['last'] ?? ''));
        $fromEmail = $fields[2];
        $subject = $fields[4] ?: 'Nuevo mensaje desde el formulario de contacto';
        $message = $fields[3];

        $to = trim((string) config('mail.admin_email', ''));
        $phone = config('merchant.nap.telephone_display', '');

        if ($to === '') {
            return back()
                ->withInput()
                ->with('contact_error', 'El formulario de contacto no está disponible por correo en este momento. Puedes llamarnos al '.$phone.'.');
        }

        $body = "Nombre: {$name}\n"
            ."Email: {$fromEmail}\n"
            ."Asunto: {$subject}\n\n"
            .$message;

        $siteName = config('merchant.nap.commercial_name', config('app.name'));

        try {
            Mail::raw($body, function ($mail) use ($to, $subject, $fromEmail, $name, $siteName) {
                $mail->to($to)
                    ->subject('[Contacto '.$siteName.'] '.$subject)
                    ->replyTo($fromEmail, $name ?: $fromEmail);
            });
        } catch (\Throwable $e) {
            Log::error('Contact form send failed: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('contact_error', 'No se ha podido enviar el mensaje en este momento. Vuelve a intentarlo más tarde o llámanos al '.$phone.'.');
        }

        return back()->with('contact_success', 'Gracias, tu mensaje se ha enviado correctamente. Te responderemos lo antes posible.');
    }
}
