<?php

namespace Database\Seeders;

use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Testimonial;
use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\ConsultationRequest;
use App\Domains\CRM\Models\GroupInquiry;
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\WaitlistEntry;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedLeads();
        $this->seedWaitlist();
        $this->seedConsultations();
        $this->seedGroupInquiries();
        $this->seedFaqs();
        $this->seedTestimonials();
    }

    private function seedLeads(): void
    {
        $statuses = LeadStatus::cases();
        $sources = LeadSource::cases();
        $avatars = AvatarType::cases();

        for ($i = 1; $i <= 35; $i++) {
            Lead::query()->create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'source' => $sources[$i % count($sources)],
                'avatar_type' => $avatars[$i % count($avatars)],
                'status' => $statuses[$i % count($statuses)],
                'notes' => fake()->optional(0.6)->sentence(),
            ]);
        }
    }

    private function seedWaitlist(): void
    {
        $leads = Lead::query()->limit(30)->get();
        $statuses = ['active', 'active', 'active', 'converted', 'unsubscribed'];

        foreach ($leads as $index => $lead) {
            WaitlistEntry::query()->create([
                'lead_id' => $lead->id,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'interests' => [fake()->word(), fake()->word()],
                'source_page' => fake()->randomElement(['home', 'contact', 'support-pathways']),
                'avatar_type' => $lead->avatar_type,
                'status' => $statuses[$index % count($statuses)],
            ]);
        }
    }

    private function seedConsultations(): void
    {
        $leads = Lead::query()->skip(5)->limit(25)->get();
        $statuses = ['pending', 'contacted', 'scheduled', 'closed'];

        foreach ($leads as $index => $lead) {
            ConsultationRequest::query()->create([
                'lead_id' => $lead->id,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'message' => fake()->paragraph(),
                'preferred_contact_method' => fake()->randomElement(['email', 'phone', 'either']),
                'source_page' => 'contact',
                'avatar_type' => $lead->avatar_type,
                'status' => $statuses[$index % count($statuses)],
            ]);
        }
    }

    private function seedGroupInquiries(): void
    {
        $leads = Lead::query()->skip(10)->limit(20)->get();
        $statuses = ['pending', 'contacted', 'confirmed', 'closed'];

        foreach ($leads as $index => $lead) {
            GroupInquiry::query()->create([
                'lead_id' => $lead->id,
                'host_name' => $lead->fullName(),
                'host_email' => $lead->email,
                'host_phone' => $lead->phone,
                'event_name' => fake()->randomElement(['Wellness Retreat', 'Bridal Party', 'Corporate Wellness Day']),
                'event_date' => fake()->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
                'guest_count' => fake()->numberBetween(4, 20),
                'message' => fake()->paragraph(),
                'status' => $statuses[$index % count($statuses)],
            ]);
        }
    }

    private function seedFaqs(): void
    {
        $pages = ['home', 'support-pathways', 'your-experience', 'wellness-journey', 'contact', null];

        for ($i = 1; $i <= 15; $i++) {
            Faq::query()->create([
                'question' => fake()->sentence().'?',
                'answer' => fake()->paragraph(2),
                'page_slug' => $pages[$i % count($pages)],
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        $images = [
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
        ];

        for ($i = 1; $i <= 12; $i++) {
            Testimonial::query()->create([
                'author_name' => fake()->name(),
                'quote' => fake()->paragraph(3),
                'attribution' => fake()->randomElement(['HeartWell guest', 'Waitlist member', 'Consultation client']),
                'image_path' => $images[$i % count($images)],
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }
}
