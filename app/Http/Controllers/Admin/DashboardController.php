<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NewsPost;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // For guests, show limited data or redirect to a guest-friendly page
            return $this->guestDashboard();
        }

        // Original dashboard code for authenticated users
        $totalAlumni = DB::table('alumni')->count();

        $genderDistribution = DB::table('alumni')
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();
    
        $graduationYearTrends = DB::table('alumni')
            ->select('year_graduated', DB::raw('count(*) as count'))
            ->groupBy('year_graduated')
            ->orderBy('year_graduated')
            ->pluck('count', 'year_graduated')
            ->toArray();
    
        $topIndustries = DB::table('alumni')
            ->select('industry', DB::raw('count(*) as count'))
            ->groupBy('industry')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('count', 'industry')
            ->toArray();
    
        $topDegreePrograms = DB::table('alumni')
            ->select('degree_program', DB::raw('count(*) as count'))
            ->groupBy('degree_program')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('count', 'degree_program')
            ->toArray();
    
        $jobTitlesByMajor = DB::table('alumni')
            ->select('degree_program', 'job_title', 'industry')
            ->get()
            ->groupBy('degree_program')
            ->map(function ($group) {
                $jobTitles = $group->pluck('job_title')->unique()->take(3); // Top 3 job titles per degree
                $industries = $group->pluck('industry')->unique()->take(3); // Top 3 industries per degree
                return [
                    'job_titles' => $jobTitles,
                    'industries' => $industries,
                ];
            })
            ->take(5) // Top 5 degree programs only
            ->toArray();
        
        // Active Users
        $now = Carbon::now();

        $dailyActiveUsers = User::where('last_login_at', '>=', $now->copy()->subDay())->count();
        $weeklyActiveUsers = User::where('last_login_at', '>=', $now->copy()->subWeek())->count();
        $monthlyActiveUsers = User::where('last_login_at', '>=', $now->copy()->subMonth())->count();

        $previousDayUsers = User::where('last_login_at', '>=', $now->copy()->subDays(2))
            ->where('last_login_at', '<', $now->copy()->subDay())
            ->count();
        $previousWeekUsers = User::where('last_login_at', '>=', $now->copy()->subWeeks(2))
            ->where('last_login_at', '<', $now->copy()->subWeek())
            ->count();
        $previousMonthUsers = User::where('last_login_at', '>=', $now->copy()->subMonths(2))
            ->where('last_login_at', '<', $now->copy()->subMonth())
            ->count();

        $dailyActiveUsersGrowth = $this->calculateGrowth($dailyActiveUsers, $previousDayUsers);
        $weeklyActiveUsersGrowth = $this->calculateGrowth($weeklyActiveUsers, $previousWeekUsers);
        $monthlyActiveUsersGrowth = $this->calculateGrowth($monthlyActiveUsers, $previousMonthUsers);

        $newsPosts = NewsPost::where(function($query) {
            if (auth()->user()->role === 'admin') {
                // Admin can see all posts
                $query->whereNotNull('id');
            } else {
                // Other roles see posts visible to everyone or their specific role
                $query->where('visible_to', 'everyone')
                    ->orWhere('visible_to', auth()->user()->role);
            }})
        ->orderBy('created_at', 'desc')
        ->take(10) // Limit numbers to most recent posts, adjust as needed
        ->get();

        return view('dashboard', compact(
            'dailyActiveUsers', 'weeklyActiveUsers', 'monthlyActiveUsers',
            'dailyActiveUsersGrowth','weeklyActiveUsersGrowth','monthlyActiveUsersGrowth',
            'newsPosts', 'totalAlumni',
            'genderDistribution',
            'graduationYearTrends',
            'topIndustries',
            'topDegreePrograms',
            'jobTitlesByMajor',
        ));
    }

    /**
     * Display a simplified dashboard for guest users
     */
    protected function guestDashboard()
    {
        // Get only public news posts
        $newsPosts = NewsPost::where('visible_to', 'everyone')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get alumni stats that are safe to show publicly (everything except gender)
        $totalAlumni = DB::table('alumni')->count();
        
        $graduationYearTrends = DB::table('alumni')
            ->select('year_graduated', DB::raw('count(*) as count'))
            ->groupBy('year_graduated')
            ->orderBy('year_graduated')
            ->pluck('count', 'year_graduated')
            ->toArray();

        $topIndustries = DB::table('alumni')
            ->select('industry', DB::raw('count(*) as count'))
            ->groupBy('industry')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('count', 'industry')
            ->toArray();

        $topDegreePrograms = DB::table('alumni')
            ->select('degree_program', DB::raw('count(*) as count'))
            ->groupBy('degree_program')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('count', 'degree_program')
            ->toArray();

        $jobTitlesByMajor = DB::table('alumni')
            ->select('degree_program', 'job_title', 'industry')
            ->get()
            ->groupBy('degree_program')
            ->map(function ($group) {
                $jobTitles = $group->pluck('job_title')->unique()->take(3); // Top 3 job titles per degree
                $industries = $group->pluck('industry')->unique()->take(3); // Top 3 industries per degree
                return [
                    'job_titles' => $jobTitles,
                    'industries' => $industries,
                ];
            })
            ->take(5) // Top 5 degree programs only
            ->toArray();
    
        // For guest users, we'll set these to 0 or empty values
        $dailyActiveUsers = 0;
        $weeklyActiveUsers = 0;
        $monthlyActiveUsers = 0;
        $dailyActiveUsersGrowth = 0;
        $weeklyActiveUsersGrowth = 0;
        $monthlyActiveUsersGrowth = 0;
        
        // We'll pass an empty array for gender distribution to hide that chart
        $genderDistribution = [];
        
        return view('guest-dashboard', compact(
            'dailyActiveUsers', 'weeklyActiveUsers', 'monthlyActiveUsers',
            'dailyActiveUsersGrowth', 'weeklyActiveUsersGrowth', 'monthlyActiveUsersGrowth',
            'newsPosts', 'totalAlumni',
            'genderDistribution', // Empty array
            'graduationYearTrends',
            'topIndustries',
            'topDegreePrograms',
            'jobTitlesByMajor'
        ));
    }

    private function getRegistrations($period, $limit)
    {
        switch ($period) {
            case 'daily':
                $query = User::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subDays($limit))
                ->groupBy('date')
                ->orderBy('date');
                $format = 'M d';
                break;
            case 'monthly':
                $query = User::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths($limit))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month');
                $format = 'M Y';
                break;
            case 'yearly':
                $query = User::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subYears($limit))
                ->groupBy('year')
                ->orderBy('year');
                $format = 'Y';
                break;
        }

        $results = $query->get();

        $labels = $results->map(function ($item) use ($format, $period) {
            switch ($period) {
                case 'daily':
                    return Carbon::parse($item->date)->format($format);
                case 'monthly':
                    return Carbon::createFromDate($item->year, $item->month, 1)->format($format);
                case 'yearly':
                    return $item->year;
            }
        })->toArray();

        $data = $results->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function calculateGrowth(int $current, int $previous): float
    {
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 2);
        }
        return $current > 0 ? 100 : 0;
    }
}

