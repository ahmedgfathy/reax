<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Property;
use Carbon\Carbon;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we already have leads
        $leadCount = Lead::count();
        if ($leadCount > 0) {
            $this->command->info("Skipping leads seeding, {$leadCount} leads already exist.");
            return;
        }
        
        // Get users for assignment
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn("No users found. Creating a default user for lead assignment.");
            $users = collect([User::factory()->create()]);
        }
        
        // Get properties for lead interest
        $properties = Property::all();
        if ($properties->isEmpty()) {
            $this->command->warn("No properties found. Creating some default properties.");
            $properties = collect([
                Property::factory()->create(['name' => 'Luxury Apartment']),
                Property::factory()->create(['name' => 'Beach Villa']),
                Property::factory()->create(['name' => 'Downtown Office'])
            ]);
        }
        
        // Lead status progression stages
        $leadProgressions = [
            // Cold leads
            ['new', 'contacted', 'lost'],
            ['new', 'lost'],
            // Warm leads
            ['new', 'contacted', 'qualified', 'lost'],
            ['new', 'contacted', 'qualified', 'negotiation', 'lost'],
            // Hot leads
            ['new', 'contacted', 'qualified', 'proposal', 'negotiation'],
            ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won'],
        ];

        // Create 100 leads with progressive timestamps and activity
        $this->command->info("Creating 100 leads with realistic progression and activity logs...");
        
        $startDate = Carbon::now()->subMonths(6);
        $leadCount = 100;
        
        for ($i = 0; $i < $leadCount; $i++) {
            // Select a random progression path
            $progression = $leadProgressions[array_rand($leadProgressions)];
            $currentStatus = end($progression);
            
            // Randomly select user and property
            $user = $users->random();
            $property = $properties->random();
            
            // Create base lead
            $createDate = $startDate->copy()->addMinutes(rand(0, 60 * 24 * 180)); // Random date within last 6 months
            
            // Budget based on property price if available
            $budget = $property->price ?? rand(500000, 5000000);
            if (rand(0, 10) > 7) {
                // Sometimes set higher/lower budget than property price
                $budget = $budget * (rand(70, 130) / 100);
            }
            
            // Determine source with weighted probabilities
            $sources = [
                'website' => 35,
                'referral' => 20,
                'social media' => 15,
                'direct' => 10,
                'advertisement' => 10,
                'property portal' => 5,
                'walk-in' => 5
            ];
            $source = $this->getRandomWeightedElement($sources);
            
            // Create lead data array - now correctly includes property_interest
            $leadData = [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'status' => 'new',
                'source' => $source,
                'property_interest' => $property->id,
                'budget' => $budget,
                'notes' => fake()->paragraph(),
                'assigned_to' => $user->id,
                'user_id' => $user->id, // Ensure user_id is set
                'created_at' => $createDate,
                'updated_at' => $createDate,
                'last_modified_by' => $user->id,
                'lead_class' => $this->determineLeadClass($currentStatus),
            ];
            
            // Create lead with initial "new" status
            $lead = Lead::create($leadData);
            
            // Generate activity logs for status progression
            $currentDate = $createDate->copy();
            $activityLogs = [];
            
            // Log for lead creation
            $activityLogs[] = [
                'user_id' => $user->id,
                'entity' => 'lead',
                'entity_type' => 'lead',
                'entity_id' => $lead->id,
                'action' => 'created_lead',
                'description' => "New lead created: {$lead->first_name} {$lead->last_name}",
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
            
            // Progress through statuses
            foreach ($progression as $index => $status) {
                if ($index === 0) continue; // Skip first status (new) as it's already set
                
                // Update lead status after some time
                $currentDate = $currentDate->copy()->addHours(rand(4, 72));
                
                if ($currentDate->gt(Carbon::now())) {
                    break; // Don't create future activities
                }
                
                // Update lead with new status
                $lead->update([
                    'status' => $status,
                    'updated_at' => $currentDate,
                    'last_modified_by' => $user->id
                ]);
                
                // Log the status change
                $activityLogs[] = [
                    'user_id' => $user->id,
                    'entity' => 'lead',
                    'entity_type' => 'lead',
                    'entity_id' => $lead->id,
                    'action' => 'updated_lead',
                    'description' => "Status changed to: {$status}",
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate
                ];
                
                // Add random notes sometimes
                if (rand(0, 10) > 6) {
                    $noteDate = $currentDate->copy()->addHours(rand(1, 24));
                    
                    if ($noteDate->lt(Carbon::now())) {
                        $noteText = $this->getRandomNoteByStatus($status);
                        
                        $activityLogs[] = [
                            'user_id' => $user->id,
                            'entity' => 'lead',
                            'entity_type' => 'lead',
                            'entity_id' => $lead->id,
                            'action' => 'added_note',
                            'description' => $noteText,
                            'created_at' => $noteDate,
                            'updated_at' => $noteDate
                        ];
                    }
                }
                
                // Schedule follow-up sometimes
                if (rand(0, 10) > 7) {
                    $followupDate = $currentDate->copy()->addDays(rand(1, 7));
                    
                    if ($followupDate->lt(Carbon::now())) {
                        $activityLogs[] = [
                            'user_id' => $user->id,
                            'entity' => 'lead',
                            'entity_type' => 'lead',
                            'entity_id' => $lead->id,
                            'action' => 'scheduled_event',
                            'description' => "Follow-up scheduled for " . $followupDate->format('Y-m-d H:i'),
                            'created_at' => $currentDate,
                            'updated_at' => $currentDate
                        ];
                        
                        // Mark the lead with last follow-up date
                        $lead->update([
                            'last_follow_up' => $followupDate,
                        ]);
                    }
                }
            }
            
            // Insert all activity logs
            ActivityLog::insert($activityLogs);
        }

        $this->command->info('Successfully created 100 lead records with realistic activity logs.');
    }
    
    /**
     * Get a random element based on weight
     */
    private function getRandomWeightedElement(array $weightedValues)
    {
        $rand = mt_rand(1, (int) array_sum($weightedValues));
        
        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
        
        return array_key_first($weightedValues);
    }
    
    /**
     * Determine lead class based on status
     */
    private function determineLeadClass($status)
    {
        $hotStatuses = ['proposal', 'negotiation', 'won'];
        $warmStatuses = ['qualified'];
        $coldStatuses = ['new', 'contacted', 'lost'];
        
        if (in_array($status, $hotStatuses)) {
            return 'A';
        } elseif (in_array($status, $warmStatuses)) {
            return 'B';
        } else {
            return 'C';
        }
    }
    
    /**
     * Get random note text based on lead status
     */
    private function getRandomNoteByStatus($status)
    {
        $notes = [
            'new' => [
                'Initial contact attempt via email.',
                'Lead came through the website inquiry form.',
                'Waiting for first contact.'
            ],
            'contacted' => [
                'Spoke with lead on the phone. They are interested in viewing properties next week.',
                'Sent email with initial property suggestions. Awaiting response.',
                'Left voicemail. Will follow up tomorrow.',
                'Client prefers communication via WhatsApp.'
            ],
            'qualified' => [
                'Budget confirmed between 2-3M EGP.',
                'Looking specifically for 3+ bedroom properties in New Cairo.',
                'Needs to move within 3 months. Motivated buyer.',
                'Has mortgage pre-approval from CIB.'
            ],
            'proposal' => [
                'Sent proposal for 3 properties matching their criteria.',
                'Client visited property ID-1234 today and showed strong interest.',
                'Negotiating on final price and payment terms.',
                'Client asked for additional information on payment plans.'
            ],
            'negotiation' => [
                'Offer submitted at 5% below asking price.',
                'Developer considering the payment schedule proposed.',
                'Second viewing scheduled to finalize decision.',
                'Countered with 2% discount if payment completed within 30 days.'
            ],
            'won' => [
                'Deal closed! Final price agreed at 2.4M EGP.',
                'Contract signed. Awaiting first payment.',
                'Client very satisfied with the property and service.',
                'Referral opportunity - client will recommend us to colleagues.'
            ],
            'lost' => [
                'Client went with another property not in our portfolio.',
                'Budget constraints led to cancellation.',
                'No response after multiple follow-ups.',
                'Decided to postpone property purchase for 6 months.'
            ]
        ];
        
        // If status doesn't have specific notes, use general notes
        if (!isset($notes[$status])) {
            $status = 'contacted';
        }
        
        return $notes[$status][array_rand($notes[$status])];
    }
}
