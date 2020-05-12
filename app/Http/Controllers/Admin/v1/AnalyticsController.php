<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\Period;

class AnalyticsController extends Controller
{
    public function index(){
        $data = [];
        if (request('metrics'))
            $data['metrics'] = request('metrics');
        if (request('dimensions'))
            $data['dimensions'] = request('dimensions');
        if (request('start-date'))
            $data['start-date'] = request('start-date');
        if (request('end-date'))
            $data['end-date'] = request('end-date');
        if (request('sort'))
            $data['sort'] = request('sort');
        if (request('max-results'))
            $data['max-results'] = request('max-results');



        $analytics = Analytics::performQuery(
            Period::years(1),
            'ga:sessions',
            $data
        );


        return response(['success' => true, 'data' => [
            'analytics' => $analytics->rows,
        ]]);
    }
}
