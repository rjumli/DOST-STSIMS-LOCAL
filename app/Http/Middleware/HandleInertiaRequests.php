<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\ListProgram;
use App\Models\ListDropdown;
use App\Models\ListAgency;
use App\Models\ListStatus;
use App\Models\ListExpense;
use App\Models\ListPrivilege;
use App\Models\LocationRegion;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\Dropdown\ListResource;
use App\Http\Resources\Dropdown\LocationResource;
use App\Http\Resources\Dropdown\ProgramResource;
use App\Http\Resources\Dropdown\StatusResource;
use App\Http\Resources\Staff\IndexResource as StaffResource;
use App\Http\Resources\SettingResource;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {   
        $settings = Setting::with('agency.region','semester','trimester','quarter')->first();
        $region_code = ($settings)? $settings->agency->region_code : NULL;
        $semester_year = ($settings)? $settings->year : NULL;

        return array_merge(parent::share($request), [
            'auth' => (\Auth::check()) ? new StaffResource(\Auth::user()) : '',
            'flash' => [
                'data' => session('data'),
                'message' => session('message'),
                'info' => session('info'),
                'status' => session('status'),
                'type' => session('type')
            ],
            'regions' => LocationResource::collection(LocationRegion::all()),
            'dropdowns' => ListResource::collection(ListDropdown::all()),
            'programs' => ProgramResource::collection(ListProgram::all()),
            'statuses' => StatusResource::collection(ListStatus::all()),
            'privileges' => ListPrivilege::all(),
            'agencies' => ListAgency::all(),
            'region_code' => $region_code,
            'semester_year' => $semester_year,
            'role' => (\Auth::check()) ? \Auth::user()->role : '-',
            'settings' => ($settings) ? new SettingResource($settings) : null
        ]);
    }
}
