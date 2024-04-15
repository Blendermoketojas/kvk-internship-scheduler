<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->withProfile()->create(); // Ensure your UserFactory sets up roles correctly.
        $token = JWTAuth::fromUser($user);

        return ['Authorization' => 'Bearer ' . $token];
    }

    /** @test */
    public function it_creates_company_successfully()
    {
        $headers = $this->authenticate();

        $response = $this->withHeaders($headers)->postJson('/api/v2/create-company', [
            'companyName' => 'New Company',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'company' => [
                         'company_name' => 'New Company',
                     ],
                 ]);

        $this->assertDatabaseHas('companies', ['company_name' => 'New Company']);
    }

    /** @test */
    public function it_deletes_company_successfully()
    {
        $company = Company::factory()->create();
        $headers = $this->authenticate();

        $response = $this->withHeaders($headers)->postJson('/api/v2/delete-company', [
            'companyId' => $company->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Company deleted successfully']);

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    /** @test */
    public function it_edits_company_successfully()
    {
        $company = Company::factory()->create();
        $headers = $this->authenticate();

        $response = $this->withHeaders($headers)->postJson('/api/v2/edit-company', [
            'companyId' => $company->id,
            'companyName' => 'Updated Company Name',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => 'Updated Company Name',
        ]);
    }

    /** @test */
    public function it_gets_company_details_successfully()
    {
        $company = Company::factory()->create();
        $headers = $this->authenticate();

        $response = $this->withHeaders($headers)->postJson('/api/v2/company', [
            'companyId' => $company->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $company->id,
                     'company_name' => $company->company_name,
                 ]);
    }

    /** @test */
    public function it_searches_companies_successfully()
    {
        Company::factory()->create(['company_name' => 'Findable Company']);
        $headers = $this->authenticate();

        $response = $this->withHeaders($headers)->postJson('/api/v2/search-companies', [
            'companyName' => 'n',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['company_name' => 'Findable Company']);
    }
}
