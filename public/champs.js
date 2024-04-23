
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var fields = [
{ inputId: 'intitule', divId: 'logo_div' },
{ inputId: 'logo', divId: 'email_div' },
{ inputId: 'email', divId: 'pagefb_div' },
{ inputId: 'pagefb', divId: 'pageinsta_div' }
    ];

    fields.forEach(function(field) {
    var input = document.getElementById(field.inputId);
    var div = document.getElementById(field.divId);

    if (input && div) {
    input.addEventListener('input', function() {
    var fieldValue = this.value.trim();
    var validationPassed = fieldValue !== '';

    if (validationPassed) {
    div.style.display = 'block';
} else {
    div.style.display = 'none';
}
});
}
});
});
</script>