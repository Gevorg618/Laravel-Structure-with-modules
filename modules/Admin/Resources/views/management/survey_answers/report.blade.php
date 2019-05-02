<table>
    <tr>
        <td>ID</td>
        @foreach($survey->questions AS $question)
            <td>{{ $question->title }}</td>
        @endforeach
    </tr>
    @foreach($answers AS $answer)
        <tr>
            @foreach($answer AS $key => $val)
                <td>{{ $val }}</td>
            @endforeach
        </tr>
    @endforeach
</table>