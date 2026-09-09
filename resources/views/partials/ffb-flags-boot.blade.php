<script>
    window.FFB_FLAGS = {
        base: @json(rtrim((string) ($legacyBase ?? config('ffb.home_path') ?? '/'), '/') . '/'),
        iso: @json(config('flag_iso', []))
    };
</script>
<script src="{{ url('js/ffb-flags.js') }}?v=4" defer></script>
