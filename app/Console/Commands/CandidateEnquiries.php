<?php

namespace App\Console\Commands;

use App\Models\CandidateEnquiry;
use Illuminate\Console\Command;
use DB;

class CandidateEnquiries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:candidate-enquiries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allCandidates = DB::connection('hr-portal')
            ->table('candidate_enquiries')
            ->get()
            ->toArray();

        $successfullCandidates = DB::connection('hr-portal')
            ->table('candidate_enquiries')
            ->where('status', 1)
            ->get();

        foreach ($allCandidates as $candidate) {
            if (!CandidateEnquiry::where('email', $candidate->email)->first()) {
                $cadidatesData = (array) $candidate;
                $cadidatesData['status'] = 'New Candidate';
                CandidateEnquiry::create($cadidatesData);
            }
        }

        foreach ($successfullCandidates as $successfullCandidate) {
            CandidateEnquiry::where('email', $successfullCandidate->email)
                ->update([
                    'status' => 'Successful Candidate',
                    'application_status' => 1,
                    'user_id' => 0
                ]);
        }
    }
}
