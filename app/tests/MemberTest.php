<?php

use App\User;
use App\Member;

class MemberTest extends TestCase
{
    /**
     * Create user
     *
     * @return string
     */
    public function testRegister()
    {
        // registration
        $response = $this->json ('POST', 'api/register', ['name' => 'Tom']);
        $response
            ->seeStatusCode(422)
            ->seeJson([
                'password' => ['The password field is required.'],
            ]);

        $user = factory(User::class)->make();

        $response = $this->json ('POST','api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertTrue(isset($response->response['access_token']));

        return $response->response['access_token'];
    }

    /**
     * Create a member
     *
     * @param string $token
     *
     * @depends testRegister
     *
     * @return array
     */
    public function testCreateMember(string $token)
    {
        $response = $this->json('POST', 'api/member', []);
        $this->assertTrue($response->response->getOriginalContent() == 'Unauthorized.');

        $member = factory(Member::class)->make();

        $response = $this->json('POST', 'api/member?token=' . $token, [
            'event_id' => 1,
            'firstname' => $member->firstname,
            'lastname' => $member->lastname,
            'email' => $member->email,
        ]);

        $response->seeJson(['event_id' => 1]);

        return [
            'token' => $token,
            'member_id' => $response->response['id'],
        ];
    }

    /**
     * Show a member
     *
     * @param array $params
     *
     * @depends testCreateMember
     */
    public function testShowMember(array $params)
    {
        $response = $this->json('GET', 'api/member/' . $params['member_id'] . '?token=' . $params['token']);
        $response->seeJson(['id' => $params['member_id']]);
    }

    /**
     * Update a member
     *
     * @param array $params
     *
     * @depends testCreateMember
     */
    public function testUpdateMember(array $params)
    {
        $response = $this->json('GET', 'api/member/' . $params['member_id'] . '?token=' . $params['token']);

        $response = $this->json('PUT', 'api/member/' . $params['member_id'] . '?token=' . $params['token'], [
            'event_id' => 2,
            'firstname' => $response->response['firstname'],
            'lastname' => $response->response['lastname'],
            'email' => $response->response['email'],
        ]);

        $response->seeJson([
            'event_id' => 2,
        ]);
    }

    /**
     * Get a members
     *
     * @param array $params
     *
     * @depends testCreateMember
     */
    public function testGetMembers(array $params)
    {
        $response = $this->json('GET', 'api/members/2?token=' . $params['token']);

        $this->assertTrue($response->response[0]['event_id'] == 2);
    }

    /**
     * Delete a member
     *
     * @param array $params
     *
     * @depends testCreateMember
     */
    public function testDeleteMember(array $params)
    {
        $response = $this->json('DELETE', 'api/member/' . $params['member_id'] . '?token=' . $params['token']);

        $response->seeJson([
            'id' => $params['member_id'],
        ]);
    }
}
