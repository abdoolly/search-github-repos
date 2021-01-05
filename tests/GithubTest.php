<?php

use Illuminate\Support\Carbon;

class GithubTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTopShouldGetTheTopCount()
    {
        $this->get('/search/repos?top=10');
        $items = $this->response->json('items');
        $this->assertCount(10, $items);

        $this->get('/search/repos?top=20');
        $items = $this->response->json('items');
        $this->assertCount(20, $items);

        $this->get('/search/repos?top=2');
        $items = $this->response->json('items');
        $this->assertCount(2, $items);
    }

    public function testDateShouldGetReposAfterTheDate()
    {
        $date = Carbon::parse('2020-05-05');
        $formatted = $date->format('Y-m-d');
        $this->get("/search/repos?top=10&date=$formatted");
        $repos = $this->response->json('items');
        foreach ($repos as $repo) {
            $createdAt = Carbon::parse($repo['created_at']);
            $this->assertTrue($createdAt > $date, 'Some repos have created_at before the specified date');
        }
    }

    public function testShouldGetOnlySpecifiedProgrammingLangs()
    {
        $this->get('/search/repos?top=20&searchTerm=e&langs=javascript,typescript');
        $repos = $this->response->json('items');
        foreach ($repos as $repo) {
            $this->assertTrue($repo['language'] == 'JavaScript' || $repo['language'] == 'TypeScript');
        }
    }

    public function testSearchTermOptionalIfDateAndLanguageOnlySpecified()
    {
        $this->get('/search/repos?top=10&date=2020-05-05&langs=javascript,typescript');
        $this->assertResponseOk();
    }

    public function testShouldRequireSearchTermIfLanguageSpecifiedWithoutDate()
    {
        $this->get('/search/repos?top=10&langs=javascript,typescript');
        $response = $this->response->json();
        $this->assertResponseStatus(422);
        $this->assertEquals([
            'searchTerm' => [
                'The search term field is required.'
            ]
        ], $response);
    }

    public function testShouldThrowValidationIfDateInvalid(){
        $this->get('/search/repos?top=10&date=2020-ka');
        $this->assertResponseStatus(422);
    }

    public function testShouldThrowValidationIfTopNotInteger(){
        $this->get('/search/repos?top=hello');
        $this->assertResponseStatus(422);
    }

    public function testShouldThrowValidationIfLangsHaveInvalidFormat(){
        $this->get('/search/repos?top=10&searchTerm=e&langs=javascript-typescript,java');
        $this->assertResponseStatus(422);
    }
}
