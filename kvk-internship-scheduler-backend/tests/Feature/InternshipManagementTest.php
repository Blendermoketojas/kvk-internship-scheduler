<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\UserProfile;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Contracts\Roles\Role;
use App\Models\Internship;
use App\Services\ManageInternships\Services\SearchInternshipTitlesService;

class InternshipManagementTest extends TestCase
{


    private function authenticateUserWithRole($roleName)
    {
        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create([
            'user_id' => $user->id,
            'role_id' => $roleName
        ]);
        $token = JWTAuth::fromUser($user);

        return ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'];
    }

    /** @test */
    public function it_creates_internship_successfully()
    {
        $headers = $this->authenticateUserWithRole(1);
        $company = Company::factory()->create();
        $students = UserProfile::factory()->count(3)->create(['role_id' => 5]); 
        $mentor = UserProfile::factory()->create(['role_id' => 4]); 
        $headOfInternship = UserProfile::factory()->create(['role_id' => 3]);

        $response = $this->withHeaders($headers)->postJson('/api/v2/internships', [
            'title' => 'Test Internship',
            'companyId' => $company->id,
            'users' => array_merge($students->pluck('id')->toArray(), [$mentor->id, $headOfInternship->id]),
            'dateFrom' => '2021-01-01',
            'dateTo' => '2021-06-01',
            'is_active' => 1,
            'forms' => []
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Test Internship',

            ]);

        $this->assertDatabaseHas('internships', ['title' => 'Test Internship']);
    }

    /** @test */
    public function it_deletes_internship_successfully()
    {
        $user = User::factory()->create();
        $headers = $this->authenticateUserWithRole(Role::PRAKTIKOS_VADOVAS->value);

        $company = Company::factory()->create();

        $internship = Internship::factory()->create(['company_id' => $company->id, 'created_by' => $user->id]);

        $response = $this->withHeaders($headers)->deleteJson('/api/v2/internship-delete', [
            'internshipId' => $internship->id,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('internships', ['id' => $internship->id]);
    }

    /** @test */
    public function a_student_user_can_retrieve_internship_he_belongs_to()
    {
        $company = Company::factory()->create();
        $students = UserProfile::factory()->count(3)->create(['role_id' => 5]);
        $mentor = UserProfile::factory()->create(['role_id' => 4]);
        $headOfInternship = UserProfile::factory()->create(['role_id' => 3]);

        $internship = Internship::factory()->create(['company_id' => $company->id]);
        $internship->userProfiles()->attach($students->pluck('id')->toArray());
        $internship->userProfiles()->attach($mentor->id);
        $internship->userProfiles()->attach($headOfInternship->id);
        $internship->refresh();

        $studentUser = $students->first()->user;
        $attachedProfiles = $internship->userProfiles()->get();
        $token = JWTAuth::fromUser($studentUser);

        $headers = ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'];
        $response = $this->withHeaders($headers)->getJson('/api/v2/internships');

        $response->assertStatus(200);
        $responseJson = $response->json();

        $this->assertNotEmpty($responseJson['internships']);
        $internshipIds = array_column($responseJson['internships'], 'id');
        $this->assertContains($internship->id, $internshipIds);
    }

 /** @test */
public function it_retrieves_linked_students_inactive_internships_via_controller_simplified()
{
    
$this->it_creates_internship_successfully();
    // Create a user and authenticate
    $headers = $this->authenticateUserWithRole(Role::MENTORIUS->value);

    // Make the request
    $response = $this->withHeaders($headers)->json('GET', '/api/v2/get-linked-students-inactive-internships');

    // Assert the response status is 200 OK
    $response->assertStatus(200)
    ->assertJsonStructure([
        '*' => [
            'id',
            'title',
            'company_id',
            'is_active',

        ],
    ]);

}

 /** @test */
 public function it_searches_internship_titles_successfully()
 {
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

     $internship1 = Internship::factory()->create(['title' => 'Web Development Internship']);
     $internship2 = Internship::factory()->create(['title' => 'Software Engineering Internship']);
     $internship3 = Internship::factory()->create(['title' => 'Data Science Internship']);

     $request = new \Illuminate\Http\Request();
     $request->merge(['searchString' => 'Web']);

     $service = new SearchInternshipTitlesService($request);
     $response = $service->execute();

     $titles = json_decode($response->getContent());

     $this->assertContains('Web Development Internship', $titles);
     $this->assertNotContains('Software Engineering Internship', $titles);
     $this->assertNotContains('Data Science Internship', $titles);
 }

}

