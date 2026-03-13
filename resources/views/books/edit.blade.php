<h2>Edit Book</h2>

<form action="{{ route('books.update',$book->id) }}" method="POST">

@csrf
@method('PUT')

Title<br>
<input type="text" name="title" value="{{ $book->title }}"><br><br>

Author<br>
<input type="text" name="author" value="{{ $book->author }}"><br><br>

ISBN<br>
<input type="text" name="isbn" value="{{ $book->isbn }}"><br><br>

Quantity<br>
<input type="number" name="quantity" value="{{ $book->quantity }}"><br><br>

Status<br>
<select name="status">
<option value="available">Available</option>
<option value="issued">Issued</option>
<option value="reserved">Reserved</option>
</select>

<br><br>

<button type="submit">Update Book</button>

</form>
