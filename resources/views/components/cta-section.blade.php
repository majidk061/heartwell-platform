<section class="bg-hw-dusty-blue-light/40 hw-section">
    <x-layout.page-container narrow class="text-center">
        <h2 class="hw-section-title">You Deserve to Feel Like Yourself Again</h2>
        <p class="text-base md:text-lg text-hw-text mt-4">Whether you're feeling depleted, stuck, or simply unlike yourself — support is available.</p>
        <div class="mt-6 md:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-stretch sm:items-center">
            <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto">{{ config('heartwell.ctas.primary.label') }}</a>
            <a href="{{ route('contact') }}#waitlist" class="btn-secondary sm:w-auto">{{ config('heartwell.ctas.secondary.waitlist.label') }}</a>
        </div>
    </x-layout.page-container>
</section>
