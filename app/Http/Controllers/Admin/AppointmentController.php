<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::orderBy('created_at', 'desc');

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20);

        $countBySource = [
            'formation' => Appointment::where('source', 'formation')->count(),
            'grossiste' => Appointment::where('source', 'grossiste')->count(),
        ];
        $pending = Appointment::where('status', 'en_attente')->count();

        return view('admin.appointments.index', compact('appointments', 'countBySource', 'pending'));
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:en_attente,confirme,refuse',
            'appointment_date' => 'nullable|date|required_if:status,confirme',
            'appointment_time' => 'nullable|string|max:10|required_if:status,confirme',
            'admin_instructions' => 'nullable|string|max:3000',
        ]);

        $appointment->update($validated);

        if ($appointment->status === 'confirme') {
            Mail::raw(
                "Bonjour {$appointment->name},\n\n"
                ."Votre rendez-vous KATUISCIA a été confirmé.\n\n"
                ."Date : {$appointment->appointment_date}\n"
                ."Horaire : {$appointment->appointment_time}\n"
                ."Type : " . ($appointment->source === 'formation' ? 'Formation Beauté' : 'Partenariat Grossiste') . "\n"
                .($appointment->admin_instructions ? "\nInstructions :\n{$appointment->admin_instructions}\n" : '')
                ."\nÀ bientôt,\nL'équipe KATUISCIA\n"
                ."contact@katuiscia.com",
                function ($mail) use ($appointment) {
                    $mail->to($appointment->email, $appointment->name)
                         ->from('contact@katuiscia.com', 'KATUISCIA')
                         ->subject('Confirmation de votre rendez-vous KATUISCIA');
                }
            );
        }

        return back()->with('success', $appointment->status === 'confirme'
            ? 'Rendez-vous confirmé et email envoyé à ' . $appointment->email . '.'
            : 'Statut du rendez-vous mis à jour.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Rendez-vous supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = explode(',', $request->input('ids', ''));
        Appointment::whereIn('id', $ids)->delete();
        return back()->with('success', count($ids) . ' rendez-vous supprimé(s).');
    }
}
