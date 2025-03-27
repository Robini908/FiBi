@if(Session::has('_toast_script'))
    {!! Session::get('_toast_script') !!}
@endif

<script>
    window.addEventListener('toast', event => {
        window.livewire.dispatch('toast', event.detail.type, event.detail.message, event.detail.title, event.detail.duration);
    });
</script> 