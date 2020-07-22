<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Member;
use App\Mail\MemberCreated;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:members'],
        ]);

        $member = Member::create([
            'event_id' => $request->event_id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
        ]);

        Mail::to($member->email)
            ->queue(new MemberCreated("Hello, {$member->firstname}", 'You have been added to the event'));

        return response()->json($member);
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);

        return response()->json($member);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $this->validate($request, [
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('members')->ignore($member->id),],
        ]);

        $member->event_id = $request->event_id;
        $member->firstname = $request->firstname;
        $member->lastname = $request->lastname;
        $member->email = $request->email;
        $member->save();

        return response()->json($member);
    }

    public function getMembers($event_id)
    {
        $members = Member::where('event_id', $event_id)->get();

        return response()->json($members);
    }

    public function delete($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json($member);
    }
}
