<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Message;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Conversation;
use App\Services\ManageMessages\ConversationService\GetUserConversationsService;
use Illuminate\Support\Facades\DB;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

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
public function user_can_create_a_conversation()
{
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Authenticate the user and get a JWT token
    $token = JWTAuth::fromUser($user);

    // Prepare the data for creating a conversation
    $data = [
        'name' => 'Test Conversation',
        'type' => 'private', // or 'group'
        'userProfileId' => [$userProfile->id],
        'message' => 'Hello World',
    ];

    // Make a POST request to the specific API endpoint
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/conversations', $data);

    // Assertions
    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Conversation created successfully with initial message',
            'conversation' => [
                'name' => 'Test Conversation',
            ],
        ]);
}
/** @test */
public function user_can_send_a_message_in_a_conversation() {
    // Create a user and authenticate
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Authenticate the user using Laravel's built-in methods, appropriate for your auth setup
    $token = JWTAuth::fromUser($user);

    // Create a conversation
    $conversation = Conversation::factory()->create([
        'name' => 'Test Conversation',
        'type' => 'private'
    ]);

    // Prepare the data for sending a message
    $data = [
        'conversation_id' => $conversation->id,
        'message' => 'Hello from the test!'
    ];

    // Make the API request to the actual route responsible for sending messages
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/sendMessage', $data);

    // Assertions to check the response status and data structure
    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Message sent successfully',
            'data' => [
                'message' => $data['message'],
                'conversation_id' => $conversation->id
            ]
        ]);

    // Further assertions to verify database entries
    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'message' => $data['message'],
        'user_id' => $user->id  // Ensure that the user ID is correct in the database
    ]);
}

/** @test */
public function can_fetch_messages_from_a_conversation()
{
    // Arrange: Create a user with a user profile, a conversation, and messages
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]); // Ensure userProfile is created

    $conversation = Conversation::factory()->create();
    $messages = Message::factory()->count(5)->for($user, 'user') // Assign the user as the owner of the message
                        ->for($conversation, 'conversation') // Assign the conversation to the message
                        ->create();

    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    // Act: Make a GET request to the specific conversation messages endpoint
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson("/api/v2/conversations/{$conversation->id}/messages");

    // Extract and transform data for comparison
    $expectedData = $messages->sortByDesc('created_at')->map(function ($message) {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'user_id' => $message->user_id,
            'message' => $message->message,
            'created_at' => $message->created_at->toJSON(),
            'updated_at' => $message->updated_at->toJSON(),
            'user_profile' => [
                'id' => $message->user->userProfile->id,
                'user_id' => $message->user->id,
                'first_name' => $message->user->userProfile->first_name,
                'last_name' => $message->user->userProfile->last_name,
                'fullname' => $message->user->userProfile->fullname,
                'created_at' => $message->user->userProfile->created_at->toJSON(),
                'updated_at' => $message->user->userProfile->updated_at->toJSON(),
            ]
        ];
    })->values()->toArray(); // Convert to array for exact matching

    // Assert: Check if the correct status and data structure is returned
    $response->assertStatus(200)
             ->assertJson([
                 'message' => 'Messages fetched successfully',
                 'data' => $expectedData
             ]);

    // Additional assertions to verify that the data contains the right number of messages
    $this->assertCount(5, $response->json('data'), "The number of messages returned was not correct.");
}

/** @test */
public function user_can_retrieve_group_conversations()
{
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
    $user->setRelation('userProfile', $userProfile);
    $token = JWTAuth::fromUser($user);

    $this->actingAs($user, 'api');  // Authenticate using Laravel's built-in function

    $groupConversation = Conversation::factory()->create(['type' => 'group']);
    $groupConversation->users()->attach($user->id);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('GET', '/api/v2/getUsersConversations');

    $response->assertStatus(200);
}

/** @test */
public function user_can_retrieve_private_conversations()
{
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
    $user->setRelation('userProfile', $userProfile);
    
    $token = JWTAuth::fromUser($user);
    $this->actingAs($user, 'api');  // Authenticate using Laravel's built-in function
    
    $otherUsers = User::factory()->count(3)->create();
    $conversations = collect();

    foreach ($otherUsers as $otherUser) {
        $conversation = Conversation::factory()->create(['type' => 'private']);
        $conversation->users()->attach([$user->id, $otherUser->id]);
        $conversations->push($conversation);
    }

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('GET', '/api/v2/getUsersConversations');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'otherParticipants' => [
                     '*' => [
                         'conversation_id',
                         'user_id',
                         'fullname',
                         'image_path'
                     ]
                 ]
             ]);
}

/** @test */
public function user_can_retrieve_private_conversations_using_service()
{
    // Arrange
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
    $user->setRelation('userProfile', $userProfile);

    // Manually authenticate the user using Laravel's built-in function
    $this->actingAs($user, 'api');  // Authenticate using Laravel's built-in function

    // Create other users and private conversations
    $otherUsers = User::factory()->count(3)->create()->each(function ($otherUser) use ($user) {
        $conversation = Conversation::factory()->create(['type' => 'private']);
        $conversation->users()->attach([$user->id, $otherUser->id]);
        DB::table('conversation_user')->insert([
            'conversation_id' => $conversation->id,
            'user_id' => $otherUser->id
        ]);
    });

    // Act: Instantiate the service with the Request (assuming the service uses the request to pull the user)
    $request = new Request();
    $service = new GetUserConversationsService($request);
    $response = $service->execute();

    // Decode the response content for assertion
    $decodedResponse = json_decode($response->getContent(), true);

    // Assert
    $this->assertEquals(200, $response->status());
    $this->assertTrue($decodedResponse['success']);
    $this->assertIsArray($decodedResponse['otherParticipants']);

    // Check that the other participants are correctly returned
    foreach ($decodedResponse['otherParticipants'] as $participant) {
        $this->assertNotEquals($user->id, $participant['user_id']);
        $this->assertArrayHasKey('conversation_id', $participant);
        $this->assertArrayHasKey('user_id', $participant);
        $this->assertArrayHasKey('fullname', $participant);
        $this->assertArrayHasKey('image_path', $participant);
    }
}

/** @test */
public function user_can_be_added_to_group_conversation()
{
    // Arrange: Create users and a group conversation
    $user = User::factory()->create();
    $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
    $otherUser = User::factory()->create(); // Additional user to add to the conversation

    $groupConversation = Conversation::factory()->create(['type' => 'group']);

    // Authenticate the user for the API request
    $token = JWTAuth::fromUser($user);
    $this->actingAs($user, 'api');

    // Prepare the data for adding a user to the group conversation
    $data = [
        'conversation_id' => $groupConversation->id,
        'user_id' => [$otherUser->id]  // Array of user IDs to be added
    ];

    // Simulate the POST request to add users to the conversation
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->json('POST', '/api/v2/add-user-to-group', $data);

    // Assert: Check the response and database changes
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Users added to group conversation successfully'
        ]);

    // Verify the user is added to the conversation in the database
    $this->assertDatabaseHas('conversation_user', [
        'conversation_id' => $groupConversation->id,
        'user_id' => $otherUser->id
    ]);
}

}