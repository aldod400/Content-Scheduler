<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Contracts\ActivityLogServiceInterface;

class LogController extends Controller
{
    protected $logService;
    public function __construct(ActivityLogServiceInterface $logService)
    {
        $this->logService = $logService;
    }
    public function index()
    {

        $logs = $this->logService->getLogs(auth('api')->user()->id);

        return view('logs.index', compact('logs'));
    }
}
