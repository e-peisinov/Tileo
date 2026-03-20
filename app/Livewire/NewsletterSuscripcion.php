<?php
namespace App\Livewire;
use App\Models\Suscriptor;
use Livewire\Component;
use Livewire\Attributes\Validate;

class NewsletterSuscripcion extends Component
{
    #[Validate('required|email|max:150')]
    public string $emailSuscripcion = '';
    public bool $suscripto = false;
    public bool $yaExistia = false;

    public function suscribir(): void
    {
        $this->validate();
        $existente = Suscriptor::where('email', strtolower($this->emailSuscripcion))->first();
        if ($existente) {
            if (!$existente->activo) {
                $existente->update(['activo' => true]);
                $this->suscripto = true;
            } else {
                $this->yaExistia = true;
            }
        } else {
            Suscriptor::create([
                'email'  => strtolower($this->emailSuscripcion),
                'activo' => true,
                'origen' => 'footer',
            ]);
            $this->suscripto = true;
        }
        $this->emailSuscripcion = '';
    }

    public function render()
    {
        return view('livewire.newsletter-suscripcion');
    }
}
