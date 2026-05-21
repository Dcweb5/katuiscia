<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'type' => 'required|in:individu,entreprise',
            'company_name' => 'nullable|string|max:255|required_if:type,entreprise',
            'siret' => 'nullable|string|max:14|required_if:type,entreprise',
            'source' => 'required|in:formation,grossiste',
            'message' => 'nullable|string|max:2000',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|string|max:10',
        ]);

        $appointment = Appointment::create($validated);

        Mail::raw(
            "Nouvelle demande de rendez-vous :\n\n"
            ."Nom : {$appointment->name}\n"
            ."Email : {$appointment->email}\n"
            ."Tél : {$appointment->phone}\n"
            ."Type : {$appointment->type}\n"
            .($appointment->company_name ? "Entreprise : {$appointment->company_name}\n" : '')
            .($appointment->siret ? "SIRET : {$appointment->siret}\n" : '')
            ."Source : {$appointment->source}\n"
            ."Date souhaitée : " . ($appointment->preferred_date ?? 'Non précisée') . "\n"
            ."Créneau : " . ($appointment->preferred_time ?? 'Non précisé') . "\n"
            .($appointment->message ? "Message : {$appointment->message}\n" : '')
            ."\n---\nConsultez et gérez ce rendez-vous : " . url('/admin/rendezvous'),
            function ($mail) {
                $mail->to('contact@katuiscia.com', 'KATUISCIA')
                     ->subject('Nouvelle demande de rendez-vous');
            }
        );

        return back()->with('success', 'Votre demande a été envoyée. Nous vous contacterons rapidement pour confirmer votre rendez-vous.');
    }
}
