<?php

namespace App\Http\Controllers\HRMS;

use App\Http\Controllers\Controller;
use App\Models\HRMS\DailyAttendance;
use App\Models\HRMS\Employee;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlackController extends Controller
{

    public function checkIn(Request $request)
    {
        Log::info('checkIn', [$request->all()]);

        // Get the signing secret from your settings
        $signingSecret = decrypt(Setting::where('key', 'signing_secret')->first()->value);


        // Get the Slack signature from the request headers
        $slackSignature = $request->header('X-Slack-Signature');

        // Get the timestamp from the request headers
        $timestamp = $request->header('X-Slack-Request-Timestamp');

        // Create a basestring from the version, timestamp and request body
        $basestring = 'v0:' . $timestamp . ':' . $request->getContent();

        // Hash the basestring with your signing secret
        $mySignature = 'v0=' . hash_hmac('sha256', $basestring, $signingSecret);

        // Compare the two signatures
        if (hash_equals($mySignature, $slackSignature)) {
            // The request is valid, continue with processing it

            // Get the user_id from the request data
            $slackUserId = $request->input('user_id');

            // Find the employee with this Slack user ID
            $employee = Employee::where('slack_id', $slackUserId)->first();

            // Check if the employee was found
            if ($employee) {
                // Get the current date
                $date = now();

                // Find if the employee already has a check-in for this date
                $attendance = DailyAttendance::firstOrNew([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                ]);

                // If the check-in is not set, it means it's a new record or the employee hasn't checked in yet for this date
                if (!$attendance->check_in) {
                    $attendance->check_in = $date->format('H:i:s');
                    $attendance->attendance_type = 'slack';
                    $attendance->remarks = 'Checked in via slack';
                    $attendance->save();
                    return response('Check-in ' . $date->format('H:i:s') . ' recorded successfully.', 200);
                } else {
                    return response('Check-in already saved at ' . $attendance->check_in, 200);
                }
            } else {
                return response('Employee not found.', 404);
            }

        } else {
            // The request is not valid, respond with an error
            return response('Invalid request.', 403);
        }
    }

    public function checkOut(Request $request)
    {

        Log::info('checkIn', [$request->all()]);

        // Get the signing secret from your settings
        $signingSecret = Setting::where('key', 'signing_secret')->first()->value;

        // Get the Slack signature from the request headers
        $slackSignature = $request->header('X-Slack-Signature');

        // Get the timestamp from the request headers
        $timestamp = $request->header('X-Slack-Request-Timestamp');

        // Create a basestring from the version, timestamp and request body
        $basestring = 'v0:' . $timestamp . ':' . $request->getContent();

        // Hash the basestring with your signing secret
        $mySignature = 'v0=' . hash_hmac('sha256', $basestring, $signingSecret);

        // Compare the two signatures
        if (hash_equals($mySignature, $slackSignature)) {
            // The request is valid, continue with processing it

            // Get the user_id from the request data
            $slackUserId = $request->input('user_id');

            // Find the employee with this Slack user ID
            $employee = Employee::where('slack_id', $slackUserId)->first();

            // Check if the employee was found
            if ($employee) {
                // Get the current date
                $date = now();

                // Find if the employee already has a check-in for this date
                $attendance = DailyAttendance::where([
                    ['employee_id', '=', $employee->id],
                    ['date', '=', $date->format('Y-m-d')],
                ])->first();

                // If the attendance record doesn't exist or check-in is not set, it means the employee hasn't checked in yet for this date
                if (!$attendance || !$attendance->check_in) {
                    return response('No check-in record found for today. Please check in before checking out.', 200);
                } else {
                    // If check-out is not already set, set it now
                    if (!$attendance->check_out) {
                        $attendance->check_out = $date->format('H:i:s');
                        $attendance->attendance_type = 'slack';
                        $attendance->remarks = 'Checked out via slack';
                        $attendance->save();
                        return response('Check-out ' . $date->format('H:i:s') . ' recorded successfully.', 200);
                    } else {
                        return response('Check-out already saved at ' . $attendance->check_out, 200);
                    }
                }
            } else {
                return response('Employee not found.', 404);
            }

        } else {
            // The request is not valid, respond with an error
            return response('Invalid request.', 403);
        }
    }

}
