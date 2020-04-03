<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Docs\Models\Doc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DocCrudTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $registrationUrl = '/auth/v1/register';
    private $docsUrl = 'api/v1/docs';
    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader("Accept", "application/json");

        $this->user = factory(User::class)->make();
        $response = $this->post($this->registrationUrl, $this->user->toArray());
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $this->token = $data['access_token'];


    }

    /**
     *
     * Can create doc when header has valid token
     *
     */
    public function test_can_create_doc_when_authenticated()
    {
        $this->withHeader("Accept", "application/json");
        $this->withHeader("Authorization", "Bearer " . $this->token);

        $doc = factory(Doc::class)->make()->toArray();
        $response = $this->post($this->docsUrl . "/", $doc);
        $response->assertStatus(200);
    }

    /**
     * Cannot create doc when header does not has access token
     */
    public function test_cannot_create_doc_when_not_authenticated()
    {
        $this->withHeader("Accept", "application/json");

        $doc = factory(Doc::class)->make()->toArray();
        $response = $this->post($this->docsUrl . "/", $doc);
        $response->assertStatus(401);
    }


    /**
     *
     * Can view a doc when authenticated
     *
     */
    public function test_can_view_doc_when_authenticated()
    {
        $this->withHeader("Accept", "application/json");
        $this->withHeader("Authorization", "Bearer " . $this->token);

        $doc = factory(Doc::class)->make()->toArray();
        $createDocResponse = $this->post($this->docsUrl . "/", $doc);
        $createDocResponse->assertStatus(200);
        $createDocResponse = json_decode($createDocResponse->getContent(), true);
        $docId = $createDocResponse['data']['id'];

        $viewResponse = $this->get($this->docsUrl . "/$docId");
        $viewResponse->assertStatus(200);
    }

    /**
     *
     * Cannot view a doc when authenticated
     *
     */
    public function test_cannot_view_doc_when_not_authenticated()
    {
        $this->withHeader("Accept", "application/json");
        $this->withHeader("Authorization", "Bearer " . $this->token);

        $doc = factory(Doc::class)->make()->toArray();
        $createDocResponse = $this->post($this->docsUrl . "/", $doc);
        $createDocResponse->assertStatus(200);
        $createDocResponse = json_decode($createDocResponse->getContent(), true);
        $docId = $createDocResponse['data']['id'];

        $this->flushHeaders();
        $this->withHeader("Accept", "application/json");
        $viewResponse = $this->get($this->docsUrl . "/$docId");
        $viewResponse->assertStatus(401);
    }


}
