<h2>Add Book</h2>

<form action="{{ route('books.store') }}" method="POST">
@csrf

Title<br>
<input type="text" name="title"><br><br>

Author<br>
<input type="text" name="author"><br><br>

ISBN<br>
<input type="text" name="isbn"><br><br>

Quantity<br>
<input type="number" name="quantity"><br><br>

Status<br>
<select name="status">
<option value="available">Available</option>
<option value="issued">Issued</option>
<option value="reserved">Reserved</option>
</select>

<br><br>

<button type="submit">Save Book</button>

</form>
