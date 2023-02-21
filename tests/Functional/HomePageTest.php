<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $recipes = $crawler->filter('.recipes .card');
        $this->AssertEquals(3, count($recipes));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Recettes récentes de la communauté');
    }
}
