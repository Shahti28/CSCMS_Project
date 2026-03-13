<h2>Issue Book</h2>

<form action="{{ route('issue.store') }}" method="POST">
@csrf

Student<br>
<select name="student_id">

@foreach($students as $student)
<option value="{{ $student->id }}">
{{ $student->name }}
</option>
@endforeach

</select>

<br><br>

Book<br>
<select name="book_id">

@foreach($books as $book)
<option value="{{ $book->id }}">
{{ $book->title }}
</option>
@endforeach

</select>

<br><br>

Issue Date<br>
<input type="date" name="issue_date">

<br><br>

<button type="submit">Issue Book</button>

</form>

