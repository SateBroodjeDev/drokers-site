<section class="contact-compose" id="bericht" aria-labelledby="contact-form-title">
    <h2 id="contact-form-title">{{ $site['contact_heading'] ?? 'Stuur me een bericht.' }}</h2>
    <p>{{ $site['contact_intro'] ?? 'Een vraag, een idee of gewoon kennismaken? Laat van je horen.' }}</p>
    @if($isPreview)
        <p>Versturen is uitgeschakeld in dit beheervoorbeeld.</p>
    @elseif(!empty($profile['id']))
        @if(session('contact_sent'))<div class="contact-feedback" role="status"><strong>Bedankt voor je bericht!</strong><p>Je bericht is ontvangen. Ik kan je antwoorden via het e-mailadres dat je hebt ingevuld.</p></div>@endif
        @if($errors->any())<div class="contact-feedback contact-feedback--error" role="alert"><strong>Controleer je gegevens.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form class="contact-form" action="{{ route('contact.send') }}" method="post">
            @csrf
            <div class="contact-form__row">
                <div class="contact-field"><label for="contact-name">Je naam</label><input id="contact-name" name="name" type="text" value="{{ old('name') }}" maxlength="120" autocomplete="name" required @error('name') aria-invalid="true" @enderror></div>
                <div class="contact-field"><label for="contact-email">Je e-mailadres</label><input id="contact-email" name="email" type="email" value="{{ old('email') }}" maxlength="254" autocomplete="email" required @error('email') aria-invalid="true" @enderror></div>
            </div>
            <div class="contact-field"><label for="contact-subject">Onderwerp</label><input id="contact-subject" name="subject" type="text" value="{{ old('subject') }}" maxlength="120" required @error('subject') aria-invalid="true" @enderror></div>
            <div class="contact-field"><label for="contact-message">Je bericht</label><textarea id="contact-message" name="message" rows="5" minlength="10" maxlength="5000" required @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea></div>
            <div class="contact-honeypot" aria-hidden="true"><label for="contact-website">Laat dit veld leeg</label><input id="contact-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
            <p class="contact-form__privacy">Je gegevens zijn alleen voor mij zichtbaar en worden gebruikt om je bericht te beantwoorden.@if($pages->has('page:privacy')) <a href="{{ route('privacy') }}">Meer over privacy</a>.@endif</p>
            <button class="button button--accent" type="submit">Verstuur bericht <x-site-icon name="arrow-right" /></button>
        </form>
    @else
        <p>Het contactformulier is op dit moment niet beschikbaar.</p>
    @endif
</section>
