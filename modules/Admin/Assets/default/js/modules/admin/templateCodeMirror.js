var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('source'),
{
    mode: "text/html",
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true
});