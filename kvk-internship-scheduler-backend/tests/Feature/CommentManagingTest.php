<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Internship;
use App\Models\Comment;
use App\Models\Company;
use App\Models\UserProfile;
use App\Services\ManageComments\Services\CreateCommentService;
use App\Services\ManageComments\Services\DeleteCommentService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Contracts\Roles\Role;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    private function authenticateUserWithRole($roleName)
    {
        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create([
            'user_id' => $user->id,
            'role_id' => $roleName
        ]);
        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }
    private function authenticateUserWithRoleSelf()
    {
        $user = User::factory()->create();
        UserProfile::factory()->create([
            'user_id' => $user->id,
            'role_id' => Role::SELF->value,
        ]);
        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    public function createInternship() {
        $authData = $this->authenticateUserWithRole(1);
        $headers = [
            'Authorization' => 'Bearer ' . $authData['token'],
            'Accept' => 'application/json'
        ];
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
    
        $internshipData = $response->json(); // Get response data as an array
        $internshipId = $internshipData['id']; // Assuming the response contains the internship ID
    
        return ['user' => $authData['user'], 'internshipId' => $internshipId];
    }
    
    /** @test */
    public function a_student_can_create_a_comment_for_an_internship() {
        $data = $this->createInternship();
        $user = $data['user'];
        $internshipId = $data['internshipId'];
    
        // Authenticate the user for the application, aligning with how the service retrieves the user
        $this->actingAs($user, 'api'); 
    
        // Prepare the data for creating a comment
        $commentData = [
            'internshipId' => $internshipId,
            'comment_name' => 'Weekly Update',
            'comment' => 'Progress has been made on the project.',
            'dateFrom' => '2021-01-02',
            'dateTo' => '2021-05-01',
        ];
    
        // Assuming the service internally uses Auth::user() or similar to obtain the user context
        $request = new Request($commentData);
        
        // Resolve the service from the container to implicitly use the authenticated user
        $service = resolve(CreateCommentService::class, ['request' => $request]);
        
        $response = $service->execute();
    
        // Assertions
        $responseArray = json_decode($response->getContent(), true);
        $this->assertDatabaseHas('comments', [
            'internship_id' => $internshipId,
            'comment_name' => 'Weekly Update'
        ]);
        $this->assertArrayHasKey('comment', $responseArray);
    }

    
}
