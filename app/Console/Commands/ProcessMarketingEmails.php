<?php
namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessMarketingEmails extends Command
{
    protected $signature = 'marketing:process';
    protected $description = 'Process abandoned carts and lead nurturing sequences';

    public function handle()
    {
        $this->processAbandonedCarts();
        $this->processLeadNurturing();
        $this->processWinback();
        $this->info('Marketing emails processed.');
    }

    private function processAbandonedCarts(): void
    {
        $carts = Cart::with('items.product')
            ->whereNull('abandoned_at')
            ->whereNull('email_sent_at')
            ->where('updated_at', '<', now()->subHour())
            ->whereHas('items')
            ->get();

        foreach ($carts as $cart) {
            $cart->update(['abandoned_at' => now()]);

            $email = $cart->email ?? ($cart->user->email ?? null);
            if (!$email || $cart->items->isEmpty()) continue;

            try {
                $total = $cart->total;
                $productName = $cart->items->first()->product->name ?? 'votre produit';
                $firstName = $cart->user->firstname ?? '';

                Mail::html(
                    $this->abandonedCartTemplate($firstName, $productName, $total, $cart->id),
                    function ($mail) use ($email) {
                        $mail->to($email)->subject('Vous avez oublié quelque chose... 🛒');
                    }
                );

                $cart->update(['email_sent_at' => now(), 'email' => $email]);
            } catch (\Exception $e) {
                \Log::error('Abandoned cart email failed: ' . $e->getMessage());
            }
        }
    }

    private function processLeadNurturing(): void
    {
        $sequences = [
            0 => [0, 'Bienvenue'], 1 => [2, 'Conseil'], 2 => [5, 'Preuve sociale'], 3 => [8, 'Dernier rappel'],
        ];

        foreach (Lead::where('opted_in', true)->where('email_sequence_step', '<', 4)->get() as $lead) {
            $step = $lead->email_sequence_step;
            if (!isset($sequences[$step])) continue;

            [$daysAfter, $type] = $sequences[$step];
            if ($lead->created_at->addDays($daysAfter)->isFuture()) continue;

            try {
                $html = match ($type) {
                    'Bienvenue' => $this->welcomeEmail($lead),
                    'Conseil' => $this->adviceEmail($lead),
                    'Preuve sociale' => $this->socialProofEmail($lead),
                    'Dernier rappel' => $this->finalReminderEmail($lead),
                    default => null,
                };

                if ($html) {
                    Mail::html($html, function ($mail) use ($lead, $type) {
                        $mail->to($lead->email, $lead->firstname)
                             ->subject(match($type) {
                                'Bienvenue' => 'Votre guide beauté personnalisé est arrivé ! 💜',
                                'Conseil' => 'Le secret des peaux éclatantes ✨',
                                'Preuve sociale' => 'Ce qu\'elles disent après 30 jours... 🌟',
                                'Dernier rappel' => 'Dernier message — votre code expire ce soir 🕛',
                                default => 'KATUISCIA'
                             });
                    });
                }

                $lead->update(['email_sequence_step' => $step + 1, 'last_emailed_at' => now()]);
            } catch (\Exception $e) {
                \Log::error("Nurturing email failed for lead {$lead->id}: " . $e->getMessage());
            }
        }
    }

    private function processWinback(): void
    {
        $leads = Lead::where('opted_in', true)
            ->whereNull('purchased')
            ->where('created_at', '<', now()->subDays(90))
            ->where(function ($q) { $q->whereNull('last_emailed_at')->orWhere('last_emailed_at', '<', now()->subDays(60)); })
            ->get();

        foreach ($leads as $lead) {
            try {
                Mail::html($this->winbackEmail($lead), function ($mail) use ($lead) {
                    $mail->to($lead->email, $lead->firstname)
                         ->subject($lead->firstname . ', votre peau nous manque... 💜');
                });
                $lead->update(['last_emailed_at' => now()]);
            } catch (\Exception $e) {
                \Log::error("Winback email failed: " . $e->getMessage());
            }
        }
    }

    // ---- EMAIL TEMPLATES ----

    private function abandonedCartTemplate($name, $product, $total, $cartId): string
    {
        $name = $name ?: '';
        return "<div style='font-family:Arial;max-width:550px;padding:20px;'>"
            ."<h2 style='color:#c4967a;'>KATUISCIA</h2>"
            ."<p>Bonjour{$name},</p>"
            ."<p>Votre <strong>{$product}</strong> vous attend encore dans votre panier !</p>"
            ."<p style='font-size:18px;'>Total : <strong>".number_format($total,2,',',' ')." €</strong></p>"
            ."<a href='".url('panier')."' style='display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;'>Finaliser ma commande</a>"
            ."<p style='color:#8b7b6e;font-size:12px;margin-top:20px;'>Livraison offerte + retour 30 jours. Aucun risque.</p></div>";
    }

    private function welcomeEmail($lead): string
    {
        $skin = $lead->quiz_responses['skin_type'] ?? 'votre peau';
        return "<div style='font-family:Arial;max-width:550px;padding:20px;'><h2 style='color:#c4967a;'>KATUISCIA</h2>"
            ."<p>Bonjour <strong>{$lead->firstname}</strong>,</p>"
            ."<p>Votre profil : <strong>peau {$skin}</strong>. Nous avons sélectionné les soins les plus adaptés.</p>"
            ."<p>Dans ce guide : la routine complète, les ingrédients à privilégier, nos 3 produits phares.</p>"
            ."<a href='".url('boutique')."' style='display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;'>Découvrir mes soins</a>"
            ."<p style='color:#8b7b6e;font-size:12px;margin-top:16px;'>Code -10% : <strong>CODE10</strong> — valable 7 jours</p></div>";
    }

    private function adviceEmail($lead): string
    {
        return "<div style='font-family:Arial;max-width:550px;padding:20px;'><h2 style='color:#c4967a;'>KATUISCIA</h2>"
            ."<p>Bonjour <strong>{$lead->firstname}</strong>,</p>"
            ."<p>73% des femmes hydratent mal leur peau. Le bon geste change tout.</p>"
            ."<p>Découvrez la routine idéale selon votre type de peau dans notre guide vidéo (3 min).</p>"
            ."<a href='".url('boutique')."?utm_source=email&utm_campaign=welcome_j2' style='display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;'>Voir le tutoriel</a>"
            ."<p style='color:#8b7b6e;font-size:12px;margin-top:16px;'>Plus que quelques jours pour votre -10% avec CODE10</p></div>";
    }

    private function socialProofEmail($lead): string
    {
        return "<div style='font-family:Arial;max-width:550px;padding:20px;'><h2 style='color:#c4967a;'>KATUISCIA</h2>"
            ."<p>Bonjour <strong>{$lead->firstname}</strong>,</p>"
            ."<p style='background:#faf7f2;padding:12px;border-left:3px solid #c4967a;'>⭐⭐⭐⭐⭐<br><em>'Ma peau est transformée après 3 semaines. Je ne me maquille plus tous les jours !'</em> — Sarah, 34 ans</p>"
            ."<p>Votre peau mérite cette transformation. Votre code <strong>CODE10</strong> expire bientôt.</p>"
            ."<a href='".url('boutique')."' style='display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;'>Découvrir les produits</a></div>";
    }

    private function finalReminderEmail($lead): string
    {
        return "<div style='font-family:Arial;max-width:550px;padding:20px;'><h2 style='color:#c4967a;'>KATUISCIA</h2>"
            ."<p>Bonjour <strong>{$lead->firstname}</strong>,</p>"
            ."<p>Dernier message : votre code <strong>CODE10</strong> expire ce soir à minuit.</p>"
            ."<p>✓ Soins sélectionnés pour vous · ✓ Échantillon offert · ✓ Livraison gratuite · ✓ 30 jours satisfait ou remboursé</p>"
            ."<a href='".url('boutique')."' style='display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;'>Commander avant minuit</a>"
            ."<p style='color:#8b7b6e;font-size:12px;margin-top:16px;'>Si vous avez des questions, répondez à cet email.</p></div>";
    }

    private function winbackEmail($lead): string
    {
        return "<div style='font-family:Arial;max-width:550px;padding:20px;'><h2 style='color:#c4967a;'>KATUISCIA</h2>"
            ."<p>Bonjour <strong>{$lead->firstname}</strong>,</p>"
            ."<p>Cela fait un moment ! Votre peau a changé ? Nous avons de nouvelles formules.</p>"
            ."<p>Code : <strong>RETOUR15</strong> — -15% sur toute la boutique, rien que pour vous.</p>"
            ."<a href='".url('boutique')."' style='display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;'>Redécouvrir la boutique</a></div>";
    }
}
