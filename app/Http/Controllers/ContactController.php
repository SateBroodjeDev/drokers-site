<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Services\PortfolioContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request, PortfolioContent $content): RedirectResponse
    {
        // Apply the same profile/page publication rules as the contact page.
        $common = $content->common('contact');
        abort_unless($common['profile']['id'] ?? null, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:254'],
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website' => ['nullable', 'string', 'max:0'],
        ], [
            'website.max' => 'Het bericht kon niet worden verwerkt. Probeer het opnieuw.',
        ], ['name' => 'naam', 'email' => 'e-mailadres', 'subject' => 'onderwerp', 'message' => 'bericht']);

        ContactMessage::create(collect($validated)->only(['name', 'email', 'subject', 'message'])->all());

        return redirect()->to(route('contact').'#bericht')->with('contact_sent', true);
    }
}
