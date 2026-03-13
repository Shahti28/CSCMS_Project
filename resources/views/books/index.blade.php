<h2>Books List</h2>

<a href="{{ route('books.create') }}">Add Book</a>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
    <th>ISBN</th>
    <th>Quantity</th>
    <th>Available</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

@foreach($books as $book)
<tr>
    <td>{{ $book->id }}</td>
    <td>{{ $book->title }}</td>
    <td>{{ $book->author }}</td>
    <td>{{ $book->isbn }}</td>
    <td>{{ $book->quantity }}</td>
    <td>{{ $book->available_quantity }}</td>

    <td>
        <a href="{{ route('books.edit', $book->id) }}">Edit</a>
        <a href="{{ route('issue.create') }}">Issue Book</a>

        @php
            // Get the latest active issue for this book (return_date is null)
            $activeIssue = $book->issues()->whereNull('return_date')->latest()->first();
        @endphp

        @if($activeIssue)
            <a href="{{ url('/return-book/'.$activeIssue->id) }}" class="btn btn-success">Return</a>
        @endif

        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
@endforeach

</table>
