<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_all_books()
    {
        $books = Book::factory(5)->create();
        
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[0]->title
            ]);
    }

    /**
     * @test
     */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /**
     * @test
     */
    public function can_create_one_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'Book Test'
        ])->assertJsonFragment([
            'title' => 'Book Test'
        ]);

        $this->assertDatabaseCount('books', 1);
    }

    /**
     * @test
     */
    public function can_update_one_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Book updated'
        ])->assertJsonFragment([
            'title' => 'Book updated'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Book updated'
        ]);
    }

    /**
     * @test
     */
    public function can_delete_one_book()
    {
        $book = Book::factory()->create();
        
        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();
    }
}
