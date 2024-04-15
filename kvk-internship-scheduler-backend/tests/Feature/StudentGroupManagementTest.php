<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\StudentGroup;
use App\Contracts\Roles\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;


class StudentGroupManagementTest extends TestCase
{
    private function authenticateUserWithRole($roleName)
    {
        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create([
            'user_id' => $user->id,
            'role' => $roleName
        ]);
        $user->setRelation('userProfile', $userProfile);
        $token = JWTAuth::fromUser($user);
    
        return ['user' => $user, 'token' => $token];
    }

/** @test */
public function authorized_user_can_create_student_group()
{
    // Arrange: Create a user with an authorized role
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create([
        'user_id' => $user->id,
        'role_id' => Role::PRODEKANAS  
    ]);

    // Authenticate the user for the API request
    $token = JWTAuth::fromUser($user);
    $this->actingAs($user, 'api');

    // Prepare the data for creating a student group
    $data = [
        'studentGroupIdentifier' => '2024INF',
        'fieldOfStudy' => 'Informatics'
    ];

    // Simulate the POST request to create a student group
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/create-student-group', $data);

    // Assert: Check the response and database changes
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'studentGroup' => [
                'group_identifier' => '2024INF',
                'field_of_study' => 'Informatics'
            ]
        ]);

    // Verify the student group is created in the database
    $this->assertDatabaseHas('student_groups', [
        'group_identifier' => '2024INF',
        'field_of_study' => 'Informatics'
    ]);
}



/** @test */
public function user_can_retrieve_a_student_group_by_id()
{
    $studentGroup = StudentGroup::factory()->create([
        'group_identifier' => '2024INF',
        'field_of_study' => 'Informatics'
    ]);

    $user = User::factory()->create();
    UserProfile::factory()->create(['user_id' => $user->id]);

    $token = JWTAuth::fromUser($user);
    $this->actingAs($user, 'api');

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/get-student-group', ['studentGroupId' => $studentGroup->id]);


    $response->assertStatus(200)
        ->assertJson([
            'id' => $studentGroup->id,
            'group_identifier' => '2024INF',
            'field_of_study' => 'Informatics',
            'created_by' => null,
            'created_at' => $studentGroup->created_at->toJSON(),
            'updated_at' => $studentGroup->updated_at->toJSON()
        ]);
}

/** @test */
public function authorized_user_can_delete_student_group()
{
    // Arrange: Create a student group and an authorized user
    $studentGroup = StudentGroup::factory()->create([
        'group_identifier' => '2025SCI',
        'field_of_study' => 'Science'
    ]);
    
    $user = User::factory()->create();
    UserProfile::factory()->create([
        'user_id' => $user->id,
        'role_id' => Role::PRODEKANAS  // Assuming PRODEKANAS is an authorized role for deleting
    ]);
    
    // Authenticate the user for the API request
    $token = JWTAuth::fromUser($user);
    $this->actingAs($user, 'api');
    

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/delete-student-group', ['studentGroupId' => $studentGroup->id]);
    
    // Assert: Check the response and database changes
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Student group deletion successful'
        ]);
    
    // Verify the student group is removed from the database
    $this->assertDatabaseMissing('student_groups', [
        'id' => $studentGroup->id
    ]);
}

/** @test */
public function authorized_user_can_update_student_group()
{
    // Arrange: Create a student group and an authorized user
    $studentGroup = StudentGroup::factory()->create([
        'group_identifier' => '2024INF',
        'field_of_study' => 'Informatics'
    ]);
    
    $user = User::factory()->create();
    UserProfile::factory()->create([
        'user_id' => $user->id,
        'role_id' => Role::PRODEKANAS  // Assuming PRODEKANAS is an authorized role for updating
    ]);
    
    // Authenticate the user for the API request
    $token = JWTAuth::fromUser($user);
    $this->actingAs($user, 'api');

    // New data for updating the student group
    $updatedData = [
        'studentGroupId' => $studentGroup->id,
        'studentGroupIdentifier' => '2025SCI',
        'fieldOfStudy' => 'Science'
    ];
    
    // Simulate the POST request to update the student group
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/update-student-group', $updatedData);
    
    // Assert: Check the response and database changes
    $response->assertStatus(200)
        ->assertJson([
            'studentGroup' => [
                'id' => $studentGroup->id,
                'group_identifier' => '2025SCI',
                'field_of_study' => 'Science'
            ],
            'didRecordChange' => true
        ]);
    
    // Verify the student group is updated in the database
    $this->assertDatabaseHas('student_groups', [
        'id' => $studentGroup->id,
        'group_identifier' => '2025SCI',
        'field_of_study' => 'Science'
    ]);
}

}
