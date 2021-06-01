<script>
	$(function() {
		// Validation Errors
        @if ($errors->count())
            @foreach($errors->all() as $message)
                toastr.error('{{ $message }}');
            @endforeach
        @endif

		// Success message
        @if ($message = session('success'))
		    toastr.success('{{ $message }}');
        @endif
	});
</script
